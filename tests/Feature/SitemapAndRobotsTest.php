<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapAndRobotsTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_contains_only_public_pages(): void
    {
        $response = $this->get(route('sitemap'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false);
        $response->assertSee(config('seo.url').'/', false);
        $response->assertSee(config('seo.url').'/pricing', false);
        $response->assertSee(config('seo.url').'/privacy', false);
        $response->assertSee(config('seo.url').'/terms', false);
        $response->assertSee(config('seo.url').'/about', false);
        $response->assertSee(config('seo.url').'/how-it-works', false);
        $response->assertDontSee('/dashboard', false);
        $response->assertDontSee('/admin', false);
        $response->assertDontSee('/profile', false);
    }

    public function test_robots_txt_disallows_private_areas_and_references_sitemap(): void
    {
        $response = $this->get(route('robots.txt'));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('User-agent: *', false);
        $response->assertSee('Disallow: /admin/', false);
        $response->assertSee('Disallow: /api/', false);
        $response->assertSee('Disallow: /dashboard', false);
        $response->assertSee('Disallow: /profile', false);
        $response->assertSee('Sitemap: '.config('seo.url').'/sitemap.xml', false);
    }

    public function test_public_pages_include_indexable_canonical_metadata(): void
    {
        foreach ([
            'landing',
            'subscription.pricing',
            'privacy',
            'terms',
            'about',
            'how-it-works',
        ] as $routeName) {
            $response = $this->get(route($routeName));

            $response->assertOk();
            $response->assertSee('<meta name="robots" content="index, follow">', false);
            $path = parse_url(route($routeName), PHP_URL_PATH) ?: '/';
            $canonicalUrl = config('seo.url').($path === '/' ? '/' : $path);

            $response->assertSee('<link rel="canonical" href="'.$canonicalUrl.'">', false);
            $response->assertSee('<meta name="description" content="', false);
        }
    }

    public function test_public_page_includes_browser_favicon(): void
    {
        $response = $this->get(route('landing'));

        $response->assertOk();
        $response->assertSee('<link rel="icon" href="'.asset('favicon.svg').'" type="image/svg+xml">', false);
    }
}
