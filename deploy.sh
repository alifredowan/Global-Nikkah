#!/usr/bin/env bash

set -Eeuo pipefail

# Run on the configured production server:
#   sudo bash /var/www/nikah/deploy.sh
#
# Optional environment variables:
#   APP_DIR=/var/www/nikah BRANCH=main HEALTHCHECK_URL=https://globalnikah.com/up
#   PHP_FPM_SERVICE=php8.4-fpm QUEUE_SERVICE=nikah-queue REVERB_SERVICE=nikah-reverb
#
# Options:
#   --no-build       Skip npm install and the Vite production build
#   --no-migrate     Skip database migrations
#   --no-services    Do not restart configured systemd services
#   --branch=name    Deploy another branch

APP_DIR="${APP_DIR:-/var/www/nikah}"
BRANCH="${BRANCH:-main}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
PHP_FPM_SERVICE="${PHP_FPM_SERVICE:-}"
QUEUE_SERVICE="${QUEUE_SERVICE:-globalnikkah-queue-worker}"
REVERB_SERVICE="${REVERB_SERVICE:-globalnikkah-reverb}"
WEB_USER="${WEB_USER:-www-data}"
WEB_GROUP="${WEB_GROUP:-www-data}"
HEALTHCHECK_URL="${HEALTHCHECK_URL:-}"
HEALTHCHECK_IP="${HEALTHCHECK_IP:-127.0.0.1}"
DOWN_RETRY="${DOWN_RETRY:-60}"
SYSTEMD_UNIT_DIR="${SYSTEMD_UNIT_DIR:-deploy}"
RUN_BUILD=true
RUN_MIGRATE=true
RUN_SERVICES=true

for arg in "$@"; do
    case "$arg" in
        --no-build) RUN_BUILD=false ;;
        --no-migrate) RUN_MIGRATE=false ;;
        --no-services) RUN_SERVICES=false ;;
        --branch=*) BRANCH="${arg#*=}" ;;
        *) echo "Unknown option: $arg" >&2; exit 2 ;;
    esac
done

fail() {
    echo "Error: $*" >&2
    exit 1
}

have() {
    command -v "$1" >/dev/null 2>&1
}

artisan() {
    "$PHP_BIN" artisan "$@"
}

detect_php_fpm() {
    [[ -n "$PHP_FPM_SERVICE" ]] && { printf '%s\n' "$PHP_FPM_SERVICE"; return; }
    have systemctl || return 0

    local version candidate
    version="$($PHP_BIN -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;' 2>/dev/null || true)"
    for candidate in "php${version}-fpm" php8.5-fpm php8.4-fpm php8.3-fpm php-fpm; do
        if systemctl is-active --quiet "$candidate"; then
            printf '%s\n' "$candidate"
            return
        fi
    done
}

install_unit() {
    local service="$1"
    local source="$SYSTEMD_UNIT_DIR/$service.service"
    local target="/etc/systemd/system/$service.service"

    [[ -f "$source" ]] || return 1
    install -m 0644 "$source" "$target"
}

cd "$APP_DIR" || fail "Application directory does not exist: $APP_DIR"

[[ "$EUID" -eq 0 ]] || fail "Run this script as root (or with sudo)."
[[ -f artisan && -f composer.json && -f composer.lock && -f .env ]] \
    || fail "$APP_DIR is missing artisan, .env, or Composer files."
grep -qE '^APP_KEY=base64:.+' .env \
    || fail "APP_KEY is not configured in .env."

for binary in git "$PHP_BIN" "$COMPOSER_BIN"; do
    have "$binary" || fail "Required command is missing: $binary"
done

if [[ "$RUN_BUILD" == true && -f package-lock.json ]]; then
    have node || fail "Node.js is required for the frontend build."
    have npm || fail "npm is required for the frontend build."
    node -e 'const [major, minor] = process.versions.node.split(".").map(Number); process.exit(major > 22 || (major === 22 && minor >= 12) || (major === 20 && minor >= 19) ? 0 : 1);' \
        || fail "Node.js 20.19+ or 22.12+ is required."
fi

git rev-parse --is-inside-work-tree >/dev/null || fail "$APP_DIR is not a Git checkout."
[[ -z "$(git status --porcelain --untracked-files=no)" ]] \
    || fail "The server checkout has tracked changes. Commit or resolve them first."
[[ "$(git branch --show-current)" == "$BRANCH" ]] \
    || fail "Check out '$BRANCH' before running this script."
id "$WEB_USER" >/dev/null 2>&1 || fail "Web user '$WEB_USER' does not exist."
getent group "$WEB_GROUP" >/dev/null || fail "Web group '$WEB_GROUP' does not exist."

PHP_FPM_SERVICE="$(detect_php_fpm)"
if [[ -n "$PHP_FPM_SERVICE" ]] && ! have systemctl; then
    fail "systemctl is required when PHP_FPM_SERVICE is configured."
fi
if [[ -n "$PHP_FPM_SERVICE" ]] && ! systemctl is-active --quiet "$PHP_FPM_SERVICE"; then
    fail "PHP-FPM service '$PHP_FPM_SERVICE' is not running."
fi

if have nginx; then
    nginx -t
fi

exec 9>"$(git rev-parse --git-path nikah-deploy.lock)"
flock -n 9 || fail "Another deployment is running."

echo "Fetching origin/$BRANCH..."
git fetch --prune origin "$BRANCH"
git merge-base --is-ancestor HEAD "origin/$BRANCH" \
    || fail "The server branch has diverged from origin/$BRANCH. Resolve it first."

maintenance_enabled=false
finish() {
    local exit_code=$?
    trap - EXIT
    if [[ "$maintenance_enabled" == true ]]; then
        artisan up --no-interaction || true
    fi
    if (( exit_code != 0 )); then
        echo "Deployment failed (exit code $exit_code). Check the application and service logs." >&2
    fi
}
trap finish EXIT
trap 'exit 130' INT
trap 'exit 143' TERM

echo "Enabling maintenance mode..."
artisan down --retry="$DOWN_RETRY" --no-interaction
maintenance_enabled=true

echo "Updating code from origin/$BRANCH..."
git merge --ff-only "origin/$BRANCH"

echo "Installing PHP dependencies..."
COMPOSER_ALLOW_SUPERUSER=1 "$COMPOSER_BIN" install \
    --no-dev --no-interaction --prefer-dist --optimize-autoloader
COMPOSER_ALLOW_SUPERUSER=1 "$COMPOSER_BIN" check-platform-reqs --no-dev --no-interaction

if [[ "$RUN_BUILD" == true && -f package-lock.json ]]; then
    echo "Building frontend assets..."
    npm ci --include=dev --no-audit --no-fund
    npm run build
    rm -f public/hot
else
    echo "Skipping frontend build."
fi

echo "Clearing and rebuilding Laravel caches..."
artisan optimize:clear --no-interaction
if [[ "$RUN_MIGRATE" == true ]]; then
    artisan migrate --force --no-interaction
else
    echo "Skipping database migrations."
fi
artisan storage:link --no-interaction
artisan config:cache --no-interaction
artisan route:cache --no-interaction
artisan event:cache --no-interaction
artisan view:cache --no-interaction

artisan queue:restart --no-interaction

if [[ -d public/build ]]; then
    chown -R "$WEB_USER:$WEB_GROUP" public/build
    chmod -R ugo+rX public/build
fi
chown -R "$WEB_USER:$WEB_GROUP" storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache

if [[ "$RUN_SERVICES" == true ]] && have systemctl; then
    if [[ -n "$QUEUE_SERVICE" ]]; then
        if install_unit "$QUEUE_SERVICE"; then
            systemctl daemon-reload
            systemctl enable --now "$QUEUE_SERVICE"
        else
            systemctl restart "$QUEUE_SERVICE"
        fi
        systemctl is-active --quiet "$QUEUE_SERVICE" \
            || fail "Queue service '$QUEUE_SERVICE' is not running."
    fi

    if [[ -n "$REVERB_SERVICE" ]]; then
        echo "Restarting $REVERB_SERVICE..."
        systemctl restart "$REVERB_SERVICE"
        systemctl is-active --quiet "$REVERB_SERVICE" \
            || fail "Reverb service '$REVERB_SERVICE' is not running."
    fi

    if [[ -n "$PHP_FPM_SERVICE" ]]; then
        echo "Restarting $PHP_FPM_SERVICE..."
        systemctl restart "$PHP_FPM_SERVICE"
    fi

    if have nginx; then
        nginx -t
        systemctl reload nginx
    fi
else
    echo "Skipping configured service management."
fi

artisan up --no-interaction
maintenance_enabled=false

if [[ -n "$HEALTHCHECK_URL" ]]; then
    have curl || fail "curl is required when HEALTHCHECK_URL is configured."
    echo "Checking $HEALTHCHECK_URL..."
    health_scheme="${HEALTHCHECK_URL%%://*}"
    health_authority="${HEALTHCHECK_URL#*://}"
    health_authority="${health_authority%%/*}"
    if [[ "$health_authority" == *:* ]]; then
        health_host="${health_authority%:*}"
        health_port="${health_authority##*:}"
    else
        health_host="$health_authority"
        health_port=80
        [[ "$health_scheme" == https ]] && health_port=443
    fi
    [[ -n "$health_host" && "$health_port" =~ ^[0-9]+$ ]] \
        || fail "Invalid HEALTHCHECK_URL: $HEALTHCHECK_URL"
    curl --silent --show-error --fail --max-time 15 \
        --resolve "$health_host:$health_port:$HEALTHCHECK_IP" \
        "$HEALTHCHECK_URL" >/dev/null \
        || fail "Health check failed: $HEALTHCHECK_URL"
fi

echo "Deployment completed successfully at $(git rev-parse --short HEAD)."
