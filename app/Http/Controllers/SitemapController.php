<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function sitemap(): Response
    {
        $urls = [
            $this->publicUrl('landing'),
            $this->publicUrl('subscription.pricing'),
            $this->publicUrl('privacy'),
            $this->publicUrl('terms'),
            $this->publicUrl('about'),
            $this->publicUrl('contact'),
            $this->publicUrl('how-it-works'),
        ];

        $lastModified = now()->toAtomString();
        $urlEntries = collect($urls)
            ->map(fn (string $url): string => "        <url>\n            <loc>{$url}</loc>\n            <lastmod>{$lastModified}</lastmod>\n        </url>")
            ->implode("\n");

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
            ."<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n"
            .$urlEntries
            ."\n</urlset>\n";

        return response($xml)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $robots = "User-agent: *\n"
            ."Disallow: /admin/\n"
            ."Disallow: /api/\n"
            ."Disallow: /blocked-users\n"
            ."Disallow: /bookmarks\n"
            ."Disallow: /checkout/\n"
            ."Disallow: /dashboard\n"
            ."Disallow: /discover\n"
            ."Disallow: /email/\n"
            ."Disallow: /forgot-password\n"
            ."Disallow: /interests\n"
            ."Disallow: /login\n"
            ."Disallow: /messages\n"
            ."Disallow: /notifications\n"
            ."Disallow: /profile\n"
            ."Disallow: /register\n"
            ."Disallow: /reset-password\n"
            ."Disallow: /settings\n"
            ."Disallow: /subscription/\n"
            ."Disallow: /wali/\n"
            ."Disallow: /nikah-mubarak\n"
            ."Disallow: /photos/\n"
            .'Sitemap: '.$this->publicUrl('sitemap')."\n";

        return response($robots)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    private function publicUrl(string $routeName): string
    {
        $path = parse_url(route($routeName), PHP_URL_PATH) ?: '/';

        return config('seo.url').($path === '/' ? '/' : $path);
    }
}
