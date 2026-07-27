<?php

namespace App\Socialite;

use SocialiteProviders\OIDC\EmptyEmailException;
use SocialiteProviders\OIDC\InvalidStateException;
use SocialiteProviders\OIDC\Provider as BaseOidcProvider;

class OidcProvider extends BaseOidcProvider
{
    /**
     * Identical to the parent implementation, but also keeps the raw token
     * response (including the id_token JWT) on the user so it can be used
     * as id_token_hint for RP-initiated logout.
     */
    public function user()
    {
        if ($this->user) {
            return $this->user;
        }

        if ($this->hasInvalidState()) {
            throw new InvalidStateException('Callback: invalid state.', 401);
        }

        $tokenResponse = $this->getAccessTokenResponse($this->request->input('code'));

        $payload = $this->decodeJWT($tokenResponse['id_token'], $this->request->input('code'));

        if ($this->hasEmptyEmail($payload)) {
            $payload = $this->getUserByToken($tokenResponse['access_token']);
            if (empty($payload['email'] ?? null)) {
                throw new EmptyEmailException('JWT: User has no email.', 401);
            }
        }

        $this->user = $this->mapUserToObject((array) $payload);

        return $this->user
            ->setToken($tokenResponse['access_token'])
            ->setRefreshToken($tokenResponse['refresh_token'] ?? null)
            ->setExpiresIn($tokenResponse['expires_in'])
            ->setAccessTokenResponseBody($tokenResponse);
    }
}
