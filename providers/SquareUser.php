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

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

/**
 * Square user class
 */
class SquareUser implements ResourceOwnerInterface
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId()
    {
        return null;
    }

    /**
     * Get display name.
     *
     * @return string
     */
    public function getName()
    {
        return null;
    }

    /**
     * Get first name.
     *
     * @return string|null
     */
    public function getFirstName()
    {
        return null;
    }

    /**
     * Get last name.
     *
     * @return string|null
     */
    public function getLastName()
    {
        return null;
    }

    /**
     * Get locale.
     *
     * @return string|null
     */
    public function getLocale()
    {
        return null;
    }

    /**
     * Get email address.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return null;
    }

    /**
     * Get hosted domain.
     *
     * @return string|null
     */
    public function getHostedDomain()
    {
        return null;
    }

    /**
     * Get avatar image URL.
     *
     * @return string|null
     */
    public function getAvatar()
    {
        return null;
    }

    /**
     * Get user data as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}
