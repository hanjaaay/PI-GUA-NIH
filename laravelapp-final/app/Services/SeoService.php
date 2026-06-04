<?php

namespace App\Services;

use App\Models\TouristAttraction;
use Illuminate\Support\Facades\URL;

class SeoService
{
    public function generateMetaTags($title, $description, $image = null)
    {
        return [
            'title' => $title,
            'description' => $description,
            'og:title' => $title,
            'og:description' => $description,
            'og:image' => $image ? URL::asset($image) : null,
            'og:url' => URL::current(),
            'twitter:card' => 'summary_large_image',
            'twitter:title' => $title,
            'twitter:description' => $description,
            'twitter:image' => $image ? URL::asset($image) : null,
        ];
    }

    public function generateAttractionSchema(TouristAttraction $attraction)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'TouristAttraction',
            'name' => $attraction->name,
            'description' => $attraction->description,
            'image' => URL::asset($attraction->image),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $attraction->location,
            ],
            'priceRange' => 'IDR '.number_format($attraction->price),
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => $attraction->reviews->avg('rating'),
                'reviewCount' => $attraction->reviews->count(),
            ],
        ];

        if ($attraction->category) {
            $schema['category'] = $attraction->category;
        }

        return $schema;
    }

    public function generateBreadcrumbSchema($items)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [],
        ];

        foreach ($items as $index => $item) {
            $schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
        }

        return $schema;
    }

    public function generateSitemap()
    {
        $sitemap = [
            'urls' => [
                [
                    'loc' => URL::to('/'),
                    'lastmod' => now()->toIso8601String(),
                    'changefreq' => 'daily',
                    'priority' => '1.0',
                ],
            ],
        ];

        // Add tourist attractions
        $attractions = TouristAttraction::all();
        foreach ($attractions as $attraction) {
            $sitemap['urls'][] = [
                'loc' => URL::to('/attractions/'.$attraction->id),
                'lastmod' => $attraction->updated_at->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        // Add category pages based on unique categories from attractions
        $categories = TouristAttraction::distinct()->pluck('category')->filter();
        foreach ($categories as $category) {
            $sitemap['urls'][] = [
                'loc' => URL::to('/categories/'.urlencode($category)),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        return $sitemap;
    }

    public function generateRobotsTxt()
    {
        return "User-agent: *\n".
               "Allow: /\n".
               "Disallow: /admin\n".
               "Disallow: /api\n".
               'Sitemap: '.URL::to('/sitemap.xml');
    }
}
