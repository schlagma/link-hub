<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    /**
     * Send a Content Security Policy carrying a per-request nonce.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('csp.enabled')) {
            return $next($request);
        }

        // Must happen before the view renders: Livewire, Flux and the Vite
        // tags all read this nonce while building their script tags.
        $nonce = Vite::useCspNonce();

        $response = $next($request);

        $header = config('csp.report_only')
            ? 'Content-Security-Policy-Report-Only'
            : 'Content-Security-Policy';

        $response->headers->set($header, $this->policy($nonce));

        return $response;
    }

    /**
     * Build the policy string.
     */
    protected function policy(string $nonce): string
    {
        $directives = config('csp.directives');

        $directives['script-src'][] = "'nonce-{$nonce}'";

        if ($oidcOrigin = $this->oidcOrigin()) {
            $directives['img-src'][] = $oidcOrigin;
        }

        $directives = $this->allowViteDevServer($directives);

        $policy = [];

        foreach ($directives as $directive => $sources) {
            $sources = array_values(array_unique(array_filter($sources)));

            $policy[] = $sources === []
                ? $directive
                : $directive.' '.implode(' ', $sources);
        }

        if ($reportUri = config('csp.report_uri')) {
            $policy[] = 'report-uri '.$reportUri;
        }

        return implode('; ', $policy);
    }

    /**
     * The origin of the configured OIDC provider, which is where user avatars
     * are served from. The base URL may carry a path such as a realm, so only
     * scheme, host and port are kept.
     */
    protected function oidcOrigin(): ?string
    {
        $baseUrl = config('services.oidc.base_url');

        if (! $baseUrl) {
            return null;
        }

        $parts = parse_url((string) $baseUrl);

        if (empty($parts['host'])) {
            return null;
        }

        $origin = ($parts['scheme'] ?? 'https').'://'.$parts['host'];

        return isset($parts['port'])
            ? $origin.':'.$parts['port']
            : $origin;
    }

    /**
     * While "npm run dev" is running, assets and the hot reload socket come
     * from the Vite dev server rather than from this origin.
     *
     * @param  array<string, list<string>>  $directives
     * @return array<string, list<string>>
     */
    protected function allowViteDevServer(array $directives): array
    {
        if (! Vite::isRunningHot()) {
            return $directives;
        }

        $devServer = rtrim((string) file_get_contents(public_path('hot')), '/');

        if ($devServer === '') {
            return $directives;
        }

        $socket = str_replace(['http://', 'https://'], ['ws://', 'wss://'], $devServer);

        $directives['script-src'][] = $devServer;
        $directives['style-src'][] = $devServer;
        $directives['font-src'][] = $devServer;
        $directives['connect-src'][] = $devServer;
        $directives['connect-src'][] = $socket;

        return $directives;
    }
}
