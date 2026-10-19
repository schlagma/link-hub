<?php

use Illuminate\Support\Facades\File;

/**
 * Points public_path() at a scratch directory, so these tests neither depend on
 * nor touch the icons an instance keeps in the real public/ folder.
 */
function withPublicFiles(array $files): string
{
    $dir = sys_get_temp_dir().'/link-hub-public-'.uniqid();
    File::makeDirectory($dir);

    foreach ($files as $file) {
        File::put($dir.'/'.$file, 'x');
    }

    app()->usePublicPath($dir);

    return $dir;
}

afterEach(function (): void {
    if (isset($this->publicDir)) {
        File::deleteDirectory($this->publicDir);
    }
});

it('links the icons that exist in public', function (): void {
    $this->publicDir = withPublicFiles(['favicon.ico', 'favicon.png', 'favicon.svg', 'apple-touch-icon.png']);
    $this->withoutVite();
    page('Public Page');

    $this->get('/')
        ->assertOk()
        ->assertSee('<link rel="icon" sizes="any" href="'.asset('favicon.ico').'">', false)
        ->assertSee('<link rel="icon" type="image/png" href="'.asset('favicon.png').'">', false)
        ->assertSee('<link rel="icon" type="image/svg+xml" href="'.asset('favicon.svg').'">', false)
        ->assertSee('<link rel="apple-touch-icon" href="'.asset('apple-touch-icon.png').'">', false);
});

it('links only the icons that are actually there', function (): void {
    $this->publicDir = withPublicFiles(['favicon.svg']);
    $this->withoutVite();
    page('Public Page');

    $response = $this->get('/')->assertOk();

    $response->assertSee('image/svg+xml', false);
    $response->assertDontSee('favicon.png', false);
    $response->assertDontSee('apple-touch-icon', false);
});

it('links no icons when public has none', function (): void {
    $this->publicDir = withPublicFiles([]);
    $this->withoutVite();
    page('Public Page');

    $this->get('/')
        ->assertOk()
        ->assertDontSee('rel="icon"', false)
        ->assertDontSee('rel="apple-touch-icon"', false);
});

it('shows the logo when both variants exist', function (): void {
    $this->publicDir = withPublicFiles(['logo.svg', 'logo-small.svg']);
    $this->withoutVite();
    page('Public Page');

    $this->get('/')
        ->assertOk()
        ->assertSee(asset('logo.svg'), false)
        ->assertSee(asset('logo-small.svg'), false);
});

it('falls back to the application name when a logo variant is missing', function (): void {
    $this->publicDir = withPublicFiles(['logo.svg']);
    $this->withoutVite();
    page('Public Page');

    $this->get('/')
        ->assertOk()
        ->assertDontSee('logo.svg', false)
        ->assertSee(config('app.name'), false);
});

it('shows the logo in the admin sidebar as well', function (): void {
    $this->publicDir = withPublicFiles(['logo.svg', 'logo-small.svg']);
    $this->withoutVite();

    $this->actingAs(admin_user())
        ->get('/admin')
        ->assertOk()
        ->assertSee(asset('logo.svg'), false);
});
