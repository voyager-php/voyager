<?php

namespace Voyager\Http\Security;

use Voyager\Util\Arr;

class Permission
{
    /**
     * Store registered permissions.
     * 
     * @var \Voyager\Util\Arr
     */

    private static $permissions;

    /**
     * Store permission key.
     * 
     * @var string
     */

    private $key;

    /**
     * Store user types.
     * 
     * @var \Voyager\Util\Arr
     */

    private $config;

    /**
     * Store user permission.
     * 
     * @var \Voyager\Util\Arr
     */

    private $permission;

    /**
     * Create new permission class instance.
     * 
     * @param   string $key
     * @return  void
     */

    public function __construct(string $key)
    {
        $this->key = $key;
        $this->permission = new Arr();
        $this->config = new Arr(AuthConfig::get('users'));
    }

    /**
     * Return permission key.
     * 
     * @return  string
     */

    public function key()
    {
        return $this->key;
    }

    /**
     * Add permission to all user types.
     * 
     * @return  $this
     */

    public function all()
    {
        foreach($this->config->get() as $key => $value)
        {
            $this->addPermission($key);
        }

        return $this;
    }

    /**
     * Add permission to specific users only.
     * 
     * @param   array $users
     * @return  $this
     */

    public function only(array $users)
    {
        foreach($users as $user)
        {
            $this->addPermission($user);
        }

        return $this;
    }

    /**
     * Add permission except specific users.
     * 
     * @param   array $users
     * @return  $this
     */

    public function except(array $users)
    {
        foreach($this->config->get() as $key => $user)
        {
            if(!in_array($key, $users))
            {
                $this->addPermission($key);
            }
        }

        return $this;
    }

    /**
     * Add permission to superadmin and admin users.
     * 
     * @return  $this
     */

    public function admins()
    {
        return $this->addPermission('superadmin')
                    ->addPermission('admin');
    }

    /**
     * Add permission to superadmin only.
     * 
     * @return  $this
     */

    public function superadmin()
    {
        return $this->addPermission('superadmin');
    }

    /**
     * Add permission to subadmin only.
     * 
     * @return  $this
     */

    public function subadmin()
    {
        return $this->addPermission('admin');
    }

    /**
     * Add permission to registered members only.
     * 
     * @return  $this
     */

    public function members()
    {
        return $this->addPermission('members');
    }

    /**
     * Add permission to guest only.
     */

    public function guest()
    {
        return $this->addPermission('guest');
    }

    /**
     * Add permission for a user.
     * 
     * @param   string $key
     * @return  $this
     */

    private function addPermission(string $key)
    {
        if($this->config->hasKey($key))
        {
            $this->permission->push($this->config->get($key));
        }

        return $this;
    }

    /**
     * Return user permission id.
     * 
     * @return array
     */

    public function getPermissions()
    {
        return $this->permission->get();
    }

    /**
     * Create new permission class instance.
     * 
     * @param   string $key
     * @return  $this
     */ 

    public static function set(string $key)
    {
        if(is_null(static::$permissions))
        {
            static::$permissions = new Arr();
        }

        if(!static::$permissions->hasKey($key))
        {
            static::$permissions->set($key, new self($key));
        }

        return static::$permissions->get($key);
    }

    /**
     * Return all registered permissions.
     * 
     * @return  array
     */

    public static function get()
    {
        if(is_null(static::$permissions))
        {
            static::$permissions = new Arr();
        }

        return static::$permissions->get();
    }

}