<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Modules\Oauth\Interfaces;

/**
 * Oauth client interface
 */
interface OauthClientInterface 
{  
    /**
     * Get oauth type 1 or 2 
     *    
     * @return int
     */
    public function getType();

    /**
     * Get resource info
     *
     * @param string $token
     * @return mixed
     */
    public function getResourceInfo($token);

    /**
     * Get oauth2 options
     * @return array
     */
    public function getOptions(): array;
    
}
