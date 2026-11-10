<?php

namespace App\Enums;

enum WaliPermissionLevel: string
{
    case ViewOnly = 'view_only';
    case ApproveRequired = 'approve_required';
    case FullProxy = 'full_proxy';

    public function label(): string
    {
        return match ($this) {
            self::ViewOnly => 'View Only (Advisory)',
            self::ApproveRequired => 'Approval Required',
            self::FullProxy => 'Full Proxy (Guardian Decision)',
        };
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

