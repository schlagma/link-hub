<?php

use App\Models\Link;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

// Unit tests get the application container (config, gates) but no database.
pest()->extend(TestCase::class)->in('Unit');

/**
 * Create a page. Pages are public unless a group list is given, which mirrors
 * how the visibility checks in the public components read them.
 *
 * @param  list<string>  $groups
 */
function page(string $title = 'Test Page', bool $public = true, array $groups = [], string $description = ''): Page
{
    return Page::create([
        'title' => $title,
        'description' => $description,
        'groups' => json_encode($groups),
        'public' => $public,
    ]);
}

/**
 * Create a link on the given page.
 */
function link_on(Page $page, string $title = 'Test Link', string $url = 'https://example.com', int $order = 1): Link
{
    return Link::create([
        'page' => $page->id,
        'link' => $url,
        'order' => $order,
        'title' => $title,
        'description' => '',
        'symbol' => '',
        'group' => false,
    ]);
}

/**
 * An OIDC user belonging to the given groups.
 *
 * @param  list<string>  $groups
 */
function user_in(array $groups = []): User
{
    return User::factory()->groups($groups)->create();
}

/**
 * An OIDC user belonging to the configured admin group.
 */
function admin_user(): User
{
    return User::factory()->admin()->create();
}
