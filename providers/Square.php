<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Modules\Oauth\Providers;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Http\Message\ResponseInterface;

/**
 * Square oauth provider
 */
class Square extends AbstractProvider
{
    /**
     * mode
     *
     * @var bool
     */
    protected $sandbox;


    public function getBaseAuthorizationUrl(): string
    {
        return $this->getConnectUrl('oauth2/authorize');
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->getConnectUrl('oauth2/token');
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->getConnectUrl('v2/merchants/me');        
    }
    
    public function getDefaultScopes(): array
    {
        return ['MERCHANT_PROFILE_READ'];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (empty($data['error'])) {
            return;
        }

        $message = $data['error']['type'] . ': ' . $data['error']['message'];
        throw new IdentityProviderException($message, $data['error']['code'], $data);
    }
    
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return [];
    }

    /**
     * Get a Square connect URL, depending on path.
     *
     * @param  string $path
     * @return string
     */
    public function getConnectUrl($path)
    {
        if ($this->sandbox == true) {
            return "https://connect.squareupsandbox.com/{$path}";
        }

        return "https://connect.squareup.com/{$path}";
    }

    /**
     * Requests resource owner details.
     *
     * @param  AccessToken $token
     * @return mixed
     */
    protected function fetchResourceOwnerDetails(AccessToken $token)
    {
        return [];
    }

    /**
     * Get the URL for rewnewing an access token.
     *
     * Square does not provide normal refresh tokens, and provides token
     * renewal instead.
     *
     * @return string
     */
    public function urlRenewToken()
    {
        return $this->getConnectUrl(sprintf(
            'oauth2/clients/%s/access-token/renew',
            $this->clientId
        ));
    }

    public function urlUserDetails(AccessToken $token)
    {
        return $this->getConnectUrl('v1/me');
    }
}
