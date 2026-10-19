<?php

it('shows public pages to guests', function (): void {
    page('Public Page');

    $this->get('/')
        ->assertOk()
        ->assertSee('Public Page');
});

it('hides non-public pages from guests', function (): void {
    page('Secret Page', public: false, groups: ['board']);

    $this->get('/')
        ->assertOk()
        ->assertDontSee('Secret Page');
});

it('shows a restricted page to a member of an allowed group', function (): void {
    page('Board Page', public: false, groups: ['board']);

    $this->actingAs(user_in(['board']))
        ->get('/')
        ->assertOk()
        ->assertSee('Board Page');
});

it('hides a restricted page from a user in another group', function (): void {
    page('Board Page', public: false, groups: ['board']);

    $this->actingAs(user_in(['members']))
        ->get('/')
        ->assertOk()
        ->assertDontSee('Board Page');
});

it('shows every page to an admin', function (): void {
    page('Public Page');
    page('Board Page', public: false, groups: ['board']);

    $this->actingAs(admin_user())
        ->get('/')
        ->assertOk()
        ->assertSee('Public Page')
        ->assertSee('Board Page');
});
