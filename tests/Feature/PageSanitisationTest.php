<?php

use App\Models\Page;

it('strips scripts from a page description', function (): void {
    $page = page('Page', description: '<p>Hallo</p><script>alert(1)</script>');

    expect($page->description)
        ->toContain('Hallo')
        ->not->toContain('<script>');
});

it('strips javascript hrefs from a page description', function (): void {
    $page = page('Page', description: '<a href="javascript:alert(1)">klick</a>');

    expect($page->description)->not->toContain('javascript:');
});

it('keeps harmless markup in a page description', function (): void {
    $page = page('Page', description: '<p><strong>fett</strong></p>');

    expect($page->description)->toContain('<strong>fett</strong>');
});

it('leaves an empty description untouched', function (): void {
    $page = Page::create([
        'title' => 'Page',
        'description' => '',
        'groups' => json_encode([]),
        'public' => true,
    ]);

    expect($page->description)->toBe('');
});
