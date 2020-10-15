<?php
  namespace App\Providers;

  use Laravel\Socialite\Two\AbstractProvider;
  use Laravel\Socialite\Two\ProviderInterface;
  use Laravel\Socialite\Two\User;
  use Illuminate\Support\Arr;
  class RainingDreamsProvider extends AbstractProvider implements ProviderInterface {

    protected $scopes = ['openid', 'account', 'games:read'];

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://auth.rainingdreams.to/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return 'https://api.rainingdreams.to/openid/token';
    }

    public function getAccessToken($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'body'    => $this->getTokenFields($code),
        ]);

        return $this->parseAccessToken($response->getBody());
    }

    protected function getTokenFields($code)
    {
        return Arr::add(
            parent::getTokenFields($code), 'grant_type', 'authorization_code'
        );
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://api.rainingdreams.to/userinfo', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function formatScopes(array $scopes, $scopeSeparator)
    {
        return implode(' ', $scopes);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id'       => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'picture'   => $user['picture'],
            'email_verified' => $user['email_verified']
        ]);
    }
  }