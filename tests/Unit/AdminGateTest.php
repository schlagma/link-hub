<?php

use App\Models\User;
use Illuminate\Support\Facades\Gate;

function user_with_groups(array $groups): User
{
    return new User(['groups' => json_encode($groups)]);
}

it('grants admin to a member of the configured admin group', function (): void {
    config()->set('app.group_admin', 'admin');

    expect(Gate::forUser(user_with_groups(['admin']))->allows('admin'))->toBeTrue();
});

it('denies admin to a user in other groups only', function (): void {
    config()->set('app.group_admin', 'admin');

    expect(Gate::forUser(user_with_groups(['members', 'board']))->allows('admin'))->toBeFalse();
});

it('denies admin to a user with no groups', function (): void {
    config()->set('app.group_admin', 'admin');

    expect(Gate::forUser(user_with_groups([]))->allows('admin'))->toBeFalse();
});

it('honours a renamed admin group', function (): void {
    config()->set('app.group_admin', 'sturahub-admins');

    expect(Gate::forUser(user_with_groups(['sturahub-admins']))->allows('admin'))->toBeTrue();
    expect(Gate::forUser(user_with_groups(['admin']))->allows('admin'))->toBeFalse();
});
