<?php

it('loads Livewire exactly once', function (string $route, bool $asAdmin): void {
    page('Public Page');

    if ($asAdmin) {
        $this->actingAs(admin_user());
    }

    $html = $this->get($route)->assertOk()->getContent();

    // resources/js/app.js bundles Livewire and Alpine through Vite. Pulling in
    // @livewireScripts on top of that boots Alpine a second time, which dies
    // with "can't redefine non-configurable property $persist" and takes every
    // Alpine driven component, modals included, down with it.
    expect($html)
        ->toContain('window.livewireScriptConfig')
        ->not->toMatch('/<script[^>]+src="[^"]*\/livewire\.js/');
})->with([
    'public layout' => ['/', false],
    'admin layout' => ['/admin', true],
]);

it('loads the Flux assets', function (): void {
    page('Public Page');

    expect($this->get('/')->assertOk()->getContent())
        ->toContain('/flux/flux')
        ->toContain('window.Flux');
});
