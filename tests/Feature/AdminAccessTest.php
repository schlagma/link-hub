<?php

it('redirects guests to the OIDC login', function (string $route): void {
    $this->get($route)->assertRedirect('/auth/login');
})->with([
    '/admin',
    '/admin/groups',
    '/admin/page/new',
]);

it('lets any authenticated user reach the admin dashboard', function (): void {
    $this->actingAs(user_in(['members']))
        ->get('/admin')
        ->assertOk();
});

it('forbids non-admins from the admin-only screens', function (string $route): void {
    $this->actingAs(user_in(['members']))
        ->get($route)
        ->assertForbidden();
})->with([
    '/admin/groups',
    '/admin/page/new',
]);

it('lets admins reach the admin-only screens', function (string $route): void {
    $this->actingAs(admin_user())
        ->get($route)
        ->assertOk();
})->with([
    '/admin/groups',
    '/admin/page/new',
]);

it('lets an admin open the edit screen of a page', function (): void {
    $page = page('Board Page', public: false, groups: ['board']);

    $this->actingAs(admin_user())
        ->get("/admin/page/{$page->id}/edit")
        ->assertOk();
});
