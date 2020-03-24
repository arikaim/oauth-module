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

/**
 * Resource info class
 */
class ResourceInfo 
{
    /**
     * Id
     *
     * @var string
     */
    protected $id;

    /**
     * email
     *
     * @var string
     */
    protected $email;

    /**
     * User name
     *
     * @var string
     */
    protected $userName;

    /**
     * Avatar image url
     *
     * @var string
     */
    protected $avatar;

    /**
     * First name
     *
     * @var string
     */
    protected $firstName;

    /**
     * Last name
     *
     * @var string
     */
    protected $lastName;

    /**
     * Get property.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return (isset($this->{$key}) == true) ? $this->{$key} : null;          
    }

    /**
     * Set Id.
     *
     * @param string $id    
     * @return ResourceInfo
     */
    public function id($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set userName.
     *
     * @param string $userName    
     * @return ResourceInfo
     */
    public function userName($userName)
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * Set email.
     *
     * @param string $email    
     * @return ResourceInfo
     */
    public function email($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Set email.
     *
     * @param string $avatar    
     * @return ResourceInfo
     */
    public function avatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * Set First Name.
     *
     * @param string $firstName    
     * @return ResourceInfo
     */
    public function firstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Set Last Name.
     *
     * @param string $lastName    
     * @return ResourceInfo
     */
    public function lastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get resource info array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id'         => $this->id,
            'user_name'  => $this->userName,
            'avatar'     => $this->avatar,
            'first_name' => $this->firstName,
            'last_name'  => $this->lastName,
            'email'      => $this->email,
        ];
    }
}
