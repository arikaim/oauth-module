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
use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Psr\Http\Message\ResponseInterface;

use Arikaim\Modules\Oauth\Providers\SquareUser;
use Arikaim\Core\Utils\DateTime;

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

    /**
     * Get auth url
     *
     * @return string
     */
    public function getBaseAuthorizationUrl(): string
    {
        return $this->getConnectUrl('oauth2/authorize');
    }

    /**
     * Get access token url
     *
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->getConnectUrl('oauth2/token');
    }

    /**
     * Get resource owner url
     *
     * @param AccessToken $token
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->getConnectUrl('v2/merchants/me');        
    }
    
    /**
     * Get default scopes
     *
     * @return array
     */
    public function getDefaultScopes(): array
    {
        return ['MERCHANT_PROFILE_READ'];
    }

    /**
     * Check response
     *
     * @param ResponseInterface $response
     * @param mixed            $data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if (empty($data['error'])) {
            return;
        }

        $message = $data['error']['type'] . ': ' . $data['error']['message'];
        throw new IdentityProviderException($message, $data['error']['code'], $data);
    }
    
    /**
     * Creates an access token from a response.
     *
     * The grant that was used to fetch the response can be used to provide
     * additional context.
     *
     * @param  array $response
     * @param  AbstractGrant $grant
     * @return AccessTokenInterface
     */
    protected function createAccessToken(array $response, AbstractGrant $grant)
    {
        $expireTime = $response['expires_at'] ?? null;
        if (empty($expireTime) == false) {
            $response['expires_in'] = DateTime::create($expireTime)->getTimestamp() - DateTime::getCurrentTimestamp();
        }
    
        return new AccessToken($response);
    }

    /**
     * Requests and returns the resource owner of given access token.
     *
     * @param  AccessToken $token
     * @return ResourceOwnerInterface
     */
    public function getResourceOwner(AccessToken $token)
    {
        return new SquareUser([]);
    }

    /**
     * Create resource owner
     *
     * @param array       $response
     * @param AccessToken $token
     * @return void
     */
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
}
