<?php

use App\Models\User;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/login', function() {
    # Store the requested URL in the session
    session()->put('intended_url', url()->previous());

    # Redirect to the OIDC provider for authentication
    return Socialite::driver('oidc')
        ->scopes(['profile', 'email', 'groups'])
        ->redirect();
})->name('login');

Route::get('/auth/callback', function() {
    # Retrieve the requested URL from the session
    $intendedUrl = session('intended_url');

    # Get user information from the OIDC provider and update or create the user in the database
    $oidcUser = Socialite::driver('oidc')->stateless()->user();
    $user = User::updateOrCreate([
        'username' => $oidcUser->nickname,
    ], [
        'username' => $oidcUser->nickname,
        'name' => $oidcUser->name,
        'firstname' => $oidcUser->user['given_name'],
        'lastname' => $oidcUser->user['family_name'],
        'email' => $oidcUser->email,
        'groups' => json_encode($oidcUser->user['groups']) ?? json_encode([]),
        'oidc_token' => $oidcUser->token,
        'oidc_refresh_token' => $oidcUser->refreshToken,
        'id_token' => $oidcUser->accessTokenResponseBody['id_token'],
    ]);

    # Log the user in
    Auth::login($user);

    # Redirect to the requested URL
    return redirect()->intended($intendedUrl);
});

Route::get('/auth/logout', function () {
    $idToken = auth()->user()->id_token;

    # Log out the user from the application
    Auth::logout();

    # Look up the provider's RP-initiated logout endpoint via OIDC discovery
    $discoveryUrl = rtrim(config('services.oidc.base_url'), '/').'/.well-known/openid-configuration';
    $endSessionEndpoint = Http::get($discoveryUrl)->json('end_session_endpoint');

    if (! $endSessionEndpoint) {
        return redirect(url()->previous());
    }

    # Tell the OIDC provider to log out the user and redirect to the last page visited in the application
    return redirect($endSessionEndpoint.'?'.http_build_query([
        'id_token_hint' => $idToken,
        'post_logout_redirect_uri' => url()->previous(),
        'client_id' => config('services.oidc.client_id'),
    ]));
})->name('logout');
