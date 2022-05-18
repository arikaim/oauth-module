<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Modules\Oauth\Drivers;

use League\OAuth2\Client\Provider\Github;

use Arikaim\Modules\Oauth\Interfaces\OauthClientInterface;
use Arikaim\Modules\Oauth\ResourceInfo;
use Arikaim\Core\Driver\Traits\Driver;
use Arikaim\Core\Interfaces\Driver\DriverInterface;
use Arikaim\Core\Http\Url;

/**
 * Github oauth client driver class
 */
class GithubOauthDriver implements DriverInterface, OauthClientInterface
{   
    use Driver;
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDriverParams('github','oauth','Github','OAuth2 client driver for Github');
    }

    /**
     * Get oauth2 options
     * @return array
     */
    public function getOptions(): array
    {
        return [];
    }

    /**
     * Get oauth client type (1 or 2)
     *
     * @return integer
     */
    public function getType()
    {
        return 2;
    }

    /**
     * Get resource info
     *
     * @param string $token
     * @return ResourceInfo
     */
    public function getResourceInfo($token)
    {
        $user = $this->getInstance()->getResourceOwner($token);
        $userData = $user->toArray();
        $name = \explode(' ',$userData['name']);
        $firstName = $name[0];
        $lastName = (isset($name[1]) == true) ? $name[1] : '';

        $info = new ResourceInfo();
        $info
            ->id($user->getId())
            ->email($user->getEmail())
            ->userName($user->getNickname())
            ->firstName($firstName)
            ->lastName($lastName)
            ->avatar($userData['avatar_url']);

        return $info;
    }

    /**
     * Init driver
     *
     * @param Properties $properties
     * @return void
    */
    public function initDriver($properties)
    {     
        $config = $properties->getValues();    
        $action = $this->getDriverOption('action');
        $config['redirectUri'] = Url::BASE_URL . $config['redirectUri'];
     
        if (empty($action) == false) {
            $config['redirectUri'] .= '/' . $action;
        }

        $this->instance = new Github($config);                          
    }

    /**
     * Create driver config properties array
     *
     * @param Arikaim\Core\Collection\Properties $properties
     * @return array
     */
    public function createDriverConfig($properties)
    {              
        // Github app Id
        $properties->property('clientId',function($property) {
            $property
                ->title('Client Id')
                ->type('text')
                ->readonly(false)
                ->value('')
                ->default('');
        });   
        // Github app secret
        $properties->property('clientSecret',function($property) {
            $property
                ->title('Client Secret')
                ->type('text')
                ->readonly(false)
                ->value('')
                ->default('');
        }); 
        // OAuth Callback
        $properties->property('redirectUri',function($property) {
            $property
                ->title('Redirect Url')
                ->type('text')
                ->readonly(true)
                ->value('/oauth/callback/github')
                ->default('/oauth/callback/github');
        }); 
    }
}
