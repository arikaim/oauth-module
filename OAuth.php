<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c) Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Modules\Oauth;

use Arikaim\Core\Extension\Module;

/**
 * OAuth module class
 */
class OAuth extends Module
{
    /**
     * Provider reference
     *
     * @var object
     */
    private $provider;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->provider = null;
        // module details
        $this->setServiceName('oauth');  
    }
    
    
    public function create($providerClass = null, array $options = [], array $collaborators = [])
    {
        if ($providerClass == null || empty($providerClass) == true) {
            $providerClass = "League\OAuth2\Client\Provider\GenericProvider";
        }
        $this->provider = new $providerClass($options,$collaborators);

        return is_object($this->provider);
    }

    /**
     * Get provider
     *
     * @return object
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Test module
     *
     * @return boolean
     */
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
