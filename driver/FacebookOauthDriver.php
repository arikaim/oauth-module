<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Modules\Oauth\Driver;

use League\OAuth2\Client\Provider\Facebook;

use Arikaim\Modules\Oauth\Interfaces\OauthClientInterface;
use Arikaim\Modules\Oauth\ResourceInfo;
use Arikaim\Core\Driver\Traits\Driver;
use Arikaim\Core\Interfaces\Driver\DriverInterface;
use Arikaim\Core\Arikaim;

/**
 * Facebook oauth client driver class
 */
class FacebookOauthDriver implements DriverInterface, OauthClientInterface
{   
    use Driver;
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDriverParams('facebook','oauth','Facebook','OAuth2 client driver for Facebook');
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
      
        $info = new ResourceInfo();
        $info
            ->id($user->getId())
            ->email($user->getEmail())
            ->userName(null)
            ->firstName($user->getFirstName())
            ->lastName($user->getLastName())
            ->avatar($user->getPictureUrl());

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
       
        $this->instance = new Facebook($config);                          
    }

    /**
     * Create driver config properties array
     *
     * @param Arikaim\Core\Collection\Properties $properties
     * @return array
     */
    public function createDriverConfig($properties)
    {              
        // Twitter app Id
        $properties->property('clientId',function($property) {
            $property
                ->title('Client Id')
                ->type('text')
                ->readonly(false)
                ->value('')
                ->default('');
        });   
        // Twitter app secret
        $properties->property('clientSecret',function($property) {
            $property
                ->title('Client Secret')
                ->type('text')
                ->readonly(false)
                ->value('')
                ->default('');
        }); 
        // Oauth Callback
        $properties->property('redirectUri',function($property) {
            $property
                ->title('Redirect Url')
                ->type('text')
                ->readonly(true)
                ->value('https://' . Arikaim::getHost() . BASE_PATH . '/oauth/callback/facebook')
                ->default('https://' . Arikaim::getHost() . BASE_PATH . '/oauth/callback/facebook');
        }); 
        // graphApiVersion
        $properties->property('graphApiVersion',function($property) {
            $property
                ->title('Graph Api Version')
                ->type('text')
                ->readonly(true)
                ->value('v2.10')
                ->default('v2.10');
        }); 
    }
}
