<?php

it('shows a public page and its links to guests', function (): void {
    $page = page('Public Page');
    link_on($page, 'Wiki', 'https://wiki.example.com');

    $this->get("/p/{$page->id}")
        ->assertOk()
        ->assertSee('Public Page')
        ->assertSee('Wiki')
        ->assertSee('https://wiki.example.com');
});

it('orders links by their order column', function (): void {
    $page = page('Public Page');
    link_on($page, 'Second', 'https://second.example.com', order: 2);
    link_on($page, 'First', 'https://first.example.com', order: 1);

    $this->get("/p/{$page->id}")
        ->assertOk()
        ->assertSeeInOrder(['First', 'Second']);
});

it('redirects guests away from a non-public page', function (): void {
    $page = page('Secret Page', public: false, groups: ['board']);

    $this->get("/p/{$page->id}")
        ->assertRedirect('/auth/login');
});

it('forbids a user who is in none of the allowed groups', function (): void {
    $page = page('Secret Page', public: false, groups: ['board']);

    $this->actingAs(user_in(['members']))
        ->get("/p/{$page->id}")
        ->assertForbidden();
});

it('shows a restricted page to a member of an allowed group', function (): void {
    $page = page('Board Page', public: false, groups: ['board']);

    $this->actingAs(user_in(['board']))
        ->get("/p/{$page->id}")
        ->assertOk()
        ->assertSee('Board Page');
});

it('shows a restricted page to an admin', function (): void {
    $page = page('Board Page', public: false, groups: ['board']);

    $this->actingAs(admin_user())
        ->get("/p/{$page->id}")
        ->assertOk()
        ->assertSee('Board Page');
});
