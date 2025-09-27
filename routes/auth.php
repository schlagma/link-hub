<?php

use App\Models\User;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/login', function() {
    # Store the requested URL in the session
    session()->put('intended_url', url()->previous());

    # Redirect to Keycloak for authentication
    return Socialite::driver('keycloak')
        ->scopes(['profile', 'email'])
        ->redirect();
})->name('login');

Route::get('/auth/callback', function() {
    # Retrieve the requested URL from the session
    $intendedUrl = session('intended_url');

    # Get user information from Keycloak and update or create the user in the database
    $keycloakUser = Socialite::driver('keycloak')->stateless()->user();
    $user = User::updateOrCreate([
        'username' => $keycloakUser->nickname,
    ], [
        'username' => $keycloakUser->nickname,
        'name' => $keycloakUser->name,
        'firstname' => $keycloakUser->user['given_name'],
        'lastname' => $keycloakUser->user['family_name'],
        'email' => $keycloakUser->email,
        'groups' => json_encode($keycloakUser->user['groups']) ?? json_encode([]),
        'keycloak_token' => $keycloakUser->token,
        'keycloak_refresh_token' => $keycloakUser->refreshToken,
        'id_token' => $keycloakUser->accessTokenResponseBody['id_token'],
    ]);

    # Log the user in
    Auth::login($user);

    # Redirect to the requested URL
    return redirect()->intended($intendedUrl);
});

Route::get('/auth/logout', function () {
    $id_token = auth()->user()->id_token;

    # Log out the user from the application
    Auth::logout();

    # Tell Keycloak to log out the user and redirect to last page visited in the application
    return redirect(Socialite::driver('keycloak')->getLogoutUrl(url()->previous(), env('KEYCLOAK_CLIENT_ID'), $id_token));
})->name('logout');