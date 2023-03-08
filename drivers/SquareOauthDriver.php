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

use Arikaim\Modules\Oauth\Providers\Square;

use Arikaim\Modules\Oauth\ResourceInfo;
use Arikaim\Modules\Oauth\Interfaces\OauthClientInterface;
use Arikaim\Core\Driver\Traits\Driver;
use Arikaim\Core\Interfaces\Driver\DriverInterface;
use Arikaim\Core\Http\Url;

/**
 * Square oauth client driver class
 */
class SquareOauthDriver implements DriverInterface, OauthClientInterface
{   
    use Driver;
   
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDriverParams('square-oauth','oauth','Square Oauth','OAuth2 client driver for Square');
    }

    /**
     * Get oauth2 options
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'scope' => ['MERCHANT_PROFILE_READ']
        ];
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
        $data = $user->toArray();

        $info = new ResourceInfo();
        $fullName = $data['display_name'] ?? '';
        $name = \explode(' ',$fullName);
        $firstName = $name[0];
        $lastName = $name[1] ?? '';

        $info
            ->id($data['id'] ?? '')
            ->email($data['email'] ?? '')
            ->firstName($firstName)
            ->lastName($lastName)
            ->avatar($data['business_logo'] ?? '');

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
        $config['redirectUri'] = Url::BASE_URL . $config['redirectUri'];

        $this->instance = new Square([
            'clientId'          => $config['clientId'],
            'clientSecret'      => $config['clientSecret'],
            'redirectUri'       => $config['redirectUri'],
            'scope'             => ['MERCHANT_PROFILE_READ'],
            'response_type'     => 'code',
            'sandbox'           => true,
        ]);                         
    }

    /**
     * Create driver config properties array
     *
     * @param Arikaim\Core\Collection\Properties $properties
     * @return void
     */
    public function createDriverConfig($properties)
    {              
        // Stripe cleint Id
        $properties->property('clientId',function($property) {
            $property
                ->title('Client Id')
                ->type('text')
                ->readonly(false)
                ->value('')
                ->default('');
        });   
        // Stripe cleint secret
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
                ->value('/oauth/callback/square-oauth')
                ->default('/oauth/callback/square-oauth');
        }); 
    }
}
