<?php

use Illuminate\Support\Facades\Vite;

it('sends a policy on web responses', function (): void {
    page('Public Page');

    $header = $this->get('/')->assertOk()->headers->get('Content-Security-Policy');

    expect($header)
        ->toContain("default-src 'self'")
        ->toContain("object-src 'none'")
        ->toContain("base-uri 'self'")
        ->toContain("frame-ancestors 'none'");
});

it('nonces every inline script it renders', function (string $route, bool $asAdmin): void {
    page('Public Page');

    if ($asAdmin) {
        $this->actingAs(admin_user());
    }

    $response = $this->get($route)->assertOk();

    preg_match('/nonce-([A-Za-z0-9]+)/', (string) $response->headers->get('Content-Security-Policy'), $matches);
    $nonce = $matches[1] ?? null;

    expect($nonce)->not->toBeNull();

    // Every inline script in the document must carry that nonce, otherwise the
    // browser drops it and the page silently loses behaviour.
    preg_match_all('/<script(?![^>]*\ssrc=)([^>]*)>/i', $response->getContent(), $inline);

    expect($inline[1])->not->toBeEmpty();

    foreach ($inline[1] as $attributes) {
        expect($attributes)->toContain("nonce=\"{$nonce}\"");
    }
})->with([
    'public layout' => ['/', false],
    'admin layout' => ['/admin', true],
]);

it('uses a fresh nonce for every request', function (): void {
    page('Public Page');

    $first = $this->get('/')->headers->get('Content-Security-Policy');
    $second = $this->get('/')->headers->get('Content-Security-Policy');

    expect($first)->not->toBe($second);
});

it('does not allow inline scripts wholesale', function (): void {
    page('Public Page');

    $header = $this->get('/')->headers->get('Content-Security-Policy');

    expect($header)->not->toContain("'unsafe-inline'; script-src")
        ->and(str_contains((string) $header, "script-src 'self' 'unsafe-eval' 'nonce-"))->toBeTrue();
});

it('derives the image origin from the OIDC base url', function (string $baseUrl, string $expected): void {
    config()->set('services.oidc.base_url', $baseUrl);
    page('Public Page');

    $header = (string) $this->get('/')->headers->get('Content-Security-Policy');

    preg_match('/img-src ([^;]+)/', $header, $matches);

    expect($matches[1] ?? '')->toContain($expected);
})->with([
    'plain host' => ['https://auth.example.org', 'https://auth.example.org'],
    'with realm path' => ['https://auth.example.org/realms/main', 'https://auth.example.org'],
    'with port' => ['https://auth.example.org:8443/realms/main', 'https://auth.example.org:8443'],
    'http scheme' => ['http://auth.example.test', 'http://auth.example.test'],
]);

it('drops the path of the OIDC base url from the policy', function (): void {
    config()->set('services.oidc.base_url', 'https://auth.example.org/realms/main');
    page('Public Page');

    expect((string) $this->get('/')->headers->get('Content-Security-Policy'))
        ->not->toContain('/realms/main');
});

it('omits the OIDC origin when no base url is configured', function (): void {
    config()->set('services.oidc.base_url', null);
    page('Public Page');

    $header = (string) $this->get('/')->headers->get('Content-Security-Policy');

    preg_match('/img-src ([^;]+)/', $header, $matches);

    expect(trim($matches[1] ?? ''))->toBe("'self' data:");
});

it('switches to the report-only header when configured', function (): void {
    config()->set('csp.report_only', true);
    page('Public Page');

    $response = $this->get('/')->assertOk();

    expect($response->headers->get('Content-Security-Policy-Report-Only'))->not->toBeNull()
        ->and($response->headers->get('Content-Security-Policy'))->toBeNull();
});

it('appends a report uri when configured', function (): void {
    config()->set('csp.report_uri', 'https://reports.example.org/csp');
    page('Public Page');

    expect($this->get('/')->headers->get('Content-Security-Policy'))
        ->toContain('report-uri https://reports.example.org/csp');
});

it('sends no policy when disabled', function (): void {
    config()->set('csp.enabled', false);
    page('Public Page');

    $response = $this->get('/')->assertOk();

    expect($response->headers->get('Content-Security-Policy'))->toBeNull()
        ->and($response->headers->get('Content-Security-Policy-Report-Only'))->toBeNull();
});

it('nonces the inline script of the OIDC redirect page', function (): void {
    Vite::useCspNonce('testing-nonce');

    $html = view('auth.redirecting', ['url' => 'https://idp.example.org/authorize'])->render();

    expect($html)->toContain('<script nonce="testing-nonce">');
});

it('allows the Vite dev server while it is running hot', function (): void {
    $hot = public_path('hot');
    file_put_contents($hot, 'http://localhost:5173');

    try {
        page('Public Page');
        $header = $this->get('/')->headers->get('Content-Security-Policy');
    } finally {
        @unlink($hot);
    }

    expect($header)
        ->toContain('http://localhost:5173')
        ->toContain('ws://localhost:5173');
});
