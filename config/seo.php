<?php

$seoUrl = rtrim((string) env('SEO_URL', 'https://globalnikkah.com'), '/');

if (parse_url($seoUrl, PHP_URL_HOST) === 'globalnikkah.com') {
    $seoUrl = 'https://globalnikah.com';
}

return [
    'url' => $seoUrl,
];
