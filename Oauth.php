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

use League\OAuth1\Client\Credentials\TemporaryCredentials;

use Arikaim\Core\Http\Session;
use Arikaim\Core\Extension\Module;

/**
 * Oauth module class
 */
class Oauth extends Module
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
    }
    
    /**
     * Install module
     *
     * @return void
     */
    public function install()
    {
        $this->installDriver('Arikaim\\Modules\\Oauth\\Drivers\\TwitterOauthDriver');
        $this->installDriver('Arikaim\\Modules\\Oauth\\Drivers\\GithubOauthDriver');
        $this->installDriver('Arikaim\\Modules\\Oauth\\Drivers\\GoogleOauthDriver');
        $this->installDriver('Arikaim\\Modules\\Oauth\\Drivers\\FacebookOauthDriver');

        return true;
    }

    /**
     * Get temporary credentials from session
     *
     * @return TemporaryCredentials
     */
    public function getTemporaryCredentials()
    {
        $identifier = Session::get('oauth.temp.identifier',null);
        $secret = Session::get('oauth.temp.secret',null);

        $credentials = new TemporaryCredentials();
        $credentials->setIdentifier($identifier);
        $credentials->setSecret($secret);

        return $credentials;
    }

    /**
     * Save OAuth2 action
     *
     * @param string $action
     * @return void
     */
    public function saveAction($action)
    {
        Session::set('oauth.action',$action);
    }

    /**
     * Get OAuth2 action
     *    
     * @return void
     */
    public function getAction()
    {
        return Session::get('oauth.action');
    }

    /**
     * Clear OAuth2 action
     *    
     * @return void
     */
    public function clearAction()
    {
        Session::remove('oauth.action');
    }

    /**
     * Save OAuth2 state
     *
     * @param string $state
     * @return void
     */
    public function saveState($state)
    {
        Session::set('oauth.state',$state);
    }

    /**
     * Get OAuth2 state
     *    
     * @return string|null
     */
    public function getState()
    {
        return Session::get('oauth.state',null);
    }

    /**
     * Clear OAuth2 state
     *    
     * @return void
     */
    public function clearState()
    {
        Session::remove('oauth.state');
    }

    /**
     * Save temporary credentials to session
     *
     * @param TemporaryCredentials $credentials
     * @return void
     */
    public function saveTemporaryCredentials($credentials)
    {
        // store credentials
        Session::set('oauth.temp.identifier',$credentials->getIdentifier());
        Session::set('oauth.temp.secret',$credentials->getSecret());
    }

    /**
     * Clear temporary credentials from session
     *
     * @return TemporaryCredentials
     */
    public function clearTemporaryCredentials()
    {
        Session::remove('oauth.temp.identifier');
        Session::remove('oauth.temp.secret');
    }

    /**
     * Create provider
     *
     * @param string $providerClass
     * @param array $options
     * @param array $collaborators
     * @return void
     */
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
}
