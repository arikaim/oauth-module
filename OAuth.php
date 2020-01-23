<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Modules\Oauth_Client;

use Arikaim\Core\Utils\Utils;
use Arikaim\Core\Extension\Module;

class OAuth extends Module
{
    private $provider;
   
    public function __construct()
    {
        $this->provider = null;
        // module details
        $this->setServiceName('oauth');  
    }

    public function __call($method, $args) 
    {
        return Utils::call($this->provider,$method,$args);       
    }

    public function create($provider_class_name = null, array $options = [], array $collaborators = [])
    {
        if ($provider_class_name == null || empty($provider_class_name) == true) {
            $provider_class_name = "League\OAuth2\Client\Provider\GenericProvider";
        }
       
        $this->provider = new $provider_class_name($options,$collaborators);
        return is_object($this->provider);
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function test()
    {
        try {
            $test_options = ['urlAuthorize' => '*','urlAccessToken' => '*', '','urlResourceOwnerDetails' => '*'];
            $result = $this->create(null,$test_options);
            $this->provider = null;
        } catch(\Exception $e) {
            $this->error = $e->getMessage();         
            return false;
        }
        return $result;
    }
}
