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

use League\OAuth1\Client\Server\Twitter;

use Arikaim\Modules\Oauth\ResourceInfo;
use Arikaim\Modules\Oauth\Interfaces\OauthClientInterface;
use Arikaim\Core\Driver\Traits\Driver;
use Arikaim\Core\Interfaces\Driver\DriverInterface;
use Arikaim\Core\Http\Url;

/**
 * Twitter oauth client driver class
 */
class TwitterOauthDriver implements DriverInterface, OauthClientInterface
{   
    use Driver;
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDriverParams('twitter','oauth','Twitter','OAuth1 client driver for Twitter');
    }

    /**
     * Get oauth client type (1 or 2)
     *
     * @return integer
     */
    public function getType()
    {
        return 1;
    }

    /**
     * Get resource info
     *
     * @param string $token
     * @return ResourceInfo
     */
    public function getResourceInfo($token)
    {
        $user = $this->getInstance()->getUserDetails($token);
        $name = \explode(' ',$user->name);
        $firstName = $name[0];
        $lastName = (isset($name[1]) == true) ? $name[1] : '';

        $info = new ResourceInfo();
        $info
            ->id($user->uid)
            ->email($user->email)
            ->userName($user->nickname)
            ->firstName($firstName)
            ->lastName($lastName)
            ->avatar($user->profile_image_url);

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
        $config['callback_uri'] = Url::BASE_URL . $config['callback_uri'];
      
        $this->instance = new Twitter($config);                          
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
        $properties->property('identifier',function($property) {
            $property
                ->title('API Key')
                ->type('text')
                ->readonly(false)
                ->value('')
                ->default('');
        });   
        // Twitter app secret
        $properties->property('secret',function($property) {
            $property
                ->title('API Secret Key')
                ->type('text')
                ->readonly(false)
                ->value('')
                ->default('');
        }); 
        // Oauth Callback
        $properties->property('callback_uri',function($property) {
            $property
                ->title('Callback')
                ->type('text')
                ->readonly(true)
                ->value('/oauth/callback/twitter')
                ->default('/oauth/callback/twitter');
        }); 
    }
}
