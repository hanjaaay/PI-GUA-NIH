<?php

namespace App\Http\Controllers;

use App\Services\SeoService;

class SeoController extends Controller
{
    protected $seoService;

    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }

    public function sitemap()
    {
        $sitemap = $this->seoService->generateSitemap();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($sitemap['urls'] as $url) {
            $xml .= '    <url>'."\n";
            $xml .= '        <loc>'.htmlspecialchars($url['loc']).'</loc>'."\n";
            $xml .= '        <lastmod>'.htmlspecialchars($url['lastmod'] ?? '').'</lastmod>'."\n";
            $xml .= '        <changefreq>'.htmlspecialchars($url['changefreq'] ?? '').'</changefreq>'."\n";
            $xml .= '        <priority>'.htmlspecialchars($url['priority'] ?? '').'</priority>'."\n";
            $xml .= '    </url>'."\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function robots()
    {
        $content = $this->seoService->generateRobotsTxt();

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}
