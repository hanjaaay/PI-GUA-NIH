<?php

namespace App\Http\Middleware;

use App\Services\SeoService;
use Closure;
use Illuminate\Http\Request;

class SeoMiddleware
{
    protected $seoService;

    public function __construct(SeoService $seoService)
    {
        $this->seoService = $seoService;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->status() === 200 && $request->isMethod('GET')) {
            $content = $response->getContent();

            // Get meta tags based on the current route
            $metaTags = $this->getMetaTags($request);

            // Add meta tags to the head section
            $content = $this->addMetaTags($content, $metaTags);

            // Add schema markup if available
            if (isset($metaTags['schema'])) {
                $content = $this->addSchemaMarkup($content, $metaTags['schema']);
            }

            $response->setContent($content);
        }

        return $response;
    }

    protected function getMetaTags(Request $request)
    {
        $defaultTags = [
            'title' => config('app.name'),
            'description' => 'Book tickets for tourist attractions in Indonesia',
        ];

        // Add meta tags based on the current route
        if ($request->routeIs('attractions.show')) {
            $attraction = $request->route('touristAttraction');

            return $this->seoService->generateMetaTags(
                $attraction->name,
                $attraction->description,
                $attraction->image
            );
        }

        if ($request->routeIs('categories.show')) {
            $category = $request->route('category');

            return $this->seoService->generateMetaTags(
                $category->name.' - '.config('app.name'),
                'Explore '.$category->name.' tourist attractions in Indonesia'
            );
        }

        return $defaultTags;
    }

    protected function addMetaTags($content, $metaTags)
    {
        $metaHtml = '';
        foreach ($metaTags as $property => $content) {
            if (strpos($property, 'og:') === 0 || strpos($property, 'twitter:') === 0) {
                $metaHtml .= "<meta property=\"{$property}\" content=\"{$content}\">\n";
            } else {
                $metaHtml .= "<meta name=\"{$property}\" content=\"{$content}\">\n";
            }
        }

        return str_replace('</head>', $metaHtml.'</head>', $content);
    }

    protected function addSchemaMarkup($content, $schema)
    {
        $schemaHtml = '<script type="application/ld+json">'.json_encode($schema).'</script>';

        return str_replace('</head>', $schemaHtml.'</head>', $content);
    }
}
