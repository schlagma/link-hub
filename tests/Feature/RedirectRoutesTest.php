<?php

it('redirects the static routes to their configured targets', function (string $route, string $key): void {
    config()->set("app.{$key}", 'https://example.org/target');

    $this->get($route)->assertRedirect('https://example.org/target');
})->with([
    ['/imprint', 'imprint_url'],
    ['/privacy', 'privacy_url'],
    ['/source-code', 'source_code_url'],
]);
