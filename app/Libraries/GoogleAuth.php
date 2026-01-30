<?php

namespace App\Libraries;

use Config\Google as GoogleConfig;
use League\OAuth2\Client\Provider\Google;

class GoogleAuth
{
    private static function provider(): Google
    {
        $config = new GoogleConfig();

        return new Google([
            'clientId'     => $config->clientId,
            'clientSecret' => $config->clientSecret,
            'redirectUri'  => $config->redirectUri,
        ]);
    }

    /**
     * Ambil URL login Google
     */
    public static function getLoginUrl(): string
    {
        $provider = self::provider();

        $authUrl = $provider->getAuthorizationUrl([
            'scope'  => ['email', 'profile'],
            'prompt' => 'select_account', // paksa pilih akun
        ]);

        // Simpan state untuk proteksi CSRF
        session()->set('oauth2state', $provider->getState());

        return $authUrl;
    }

    /**
     * Ambil data user dari Google
     */
    public static function getUser(string $code): array
    {
        $provider = self::provider();

        $token = $provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);

        $owner = $provider->getResourceOwner($token);

        return [
            'id'            => $owner->getId(),
            'name'          => $owner->getName(),
            'email'         => $owner->getEmail(),
            'avatar'        => $owner->getAvatar(),
            'access_token'  => $token->getToken(),
            'expires'       => $token->getExpires(),
        ];
    }

    /**
     * Validasi state (anti CSRF)
     */
    public static function validateState(?string $state): bool
    {
        $sessionState = session()->get('oauth2state');
        session()->remove('oauth2state');

        return $state && $sessionState && hash_equals($sessionState, $state);
    }
}
