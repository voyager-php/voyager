<?php

namespace Voyager\Http\Security;

use Voyager\Facade\Str;
use Voyager\Util\Data\Collection;
use Voyager\Util\Http\Cookie;
use Voyager\Util\Http\Session;

class Authentication
{
    /**
     * Store authentication config data.
     * 
     * @var \Voyager\Util\Data\Collection
     */

    private $config;

    /**
     * Store permission data.
     * 
     * @var array
     */

    private $permissions;

    /**
     * Cookie object.
     * 
     * @var \Voyager\Util\Http\Cookie
     */

    private $cookie;

    /**
     * Authentication session object.
     * 
     * @var \Voyager\Util\Http\Session
     */

    private $session;

    /**
     * Permission session object.
     * 
     * @var \Voyager\Util\Http\Session
     */

    private $permission;

    /**
     * Start adding session values.
     * 
     * @var bool
     */

    private $start = false;

    /**
     * Create new authentication instance.
     * 
     * @return  void
     */

    public function __construct()
    {
        $this->config = new Collection(AuthConfig::get());
        $this->permissions = PermissionConfig::get();
        $this->cookie = new Cookie('remember_user');
        $this->permission = new Session('permission');
        $this->session = new Session('authentication', [
            'authenticated' => false,
            'auth_id'       => null,
            'user_id'       => null,
            'user_type'     => null,
            'time_in'       => null,
        ]);
    }

    /**
     * Return authentication configuration.
     * 
     * @return  mixed
     */

    public function config()
    {
        return $this->config;
    }

    /** 
     * Return true if request is authenticated.
     * 
     * @return  bool
     */

    public function authenticated()
    {
        return $this->get('authenticated') &&
               $this->get('auth_id') !== null &&
               $this->get('user_id') !== null &&
               $this->get('user_type') !== null &&
               $this->get('time_in') !== null;
    }

    /** 
     * Return true if user session is remembered.
     * 
     * @return  bool
     */

    public function isRemembered()
    {
        return !is_null($this->cookie->value());
    }

    /** 
     * Create new user session.
     * 
     * @param   string $user_id
     * @param   int $user_type
     * @param   bool $remember
     * @return  $this
     */

    public function authenticate(string $user_id, int $user_type, bool $remember = false)
    {
        if($this->isRemembered())
        {
            $this->cookie->delete();
        }

        if($remember)
        {
            $this->cookie->set($user_id)->expire($this->config->expiration);
        }

        $this->start = true;
        $this->session->set('authenticated', true);
        $this->session->set('auth_id', Str::hash(time()));
        $this->session->set('user_id', $user_id);
        $this->session->set('user_type', $user_type);
        $this->session->set('time_in', time());
        $this->updatePermissions($user_type);

        return $this;
    }

    /**
     * Update user permissions.
     * 
     * @param   int $user_type
     * @return  void
     */

    public function updatePermissions(int $user_type)
    {
        foreach($this->permissions as $key => $users)
        {
            $this->permission->set($key, in_array($user_type, $users) ? true : false);
        }
    }

    /**
     * Check if user has permission.
     * 
     * @param   string $key
     * @return  bool
     */

    public function hasPermission(string $key)
    {
        if($this->authenticated())
        {
            return $this->permission->get($key);
        }
        else
        {
            return in_array(3, $this->permissions[$key]);
        }
    }

    /** 
     * Return auth payload data.
     * 
     * @param   string $key
     * @return  mixed
     */

    public function get(string $key)
    {
        return $this->session->get($key);
    }

    /** 
     * Update auth session payload data.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function set(string $key, $value)
    {
        if($this->start)
        {
            $this->session->set($key, $value);
        }

        return $this;
    }

    /** 
     * Return authentication id.
     * 
     * @return  string
     */

    public function id()
    {
        return $this->get('auth_id');
    }

    /** 
     * Return user type.
     * 
     * @return  int
     */

    public function type()
    {
        return $this->get('user_type');
    }

    /** 
     * Return user id in string.
     * 
     * @return  string
     */

    public function userId()
    {
        return $this->get('user_id') ?? $this->cookie->value('remember_user');
    }

    /** 
     * Return time in timestamp.
     * 
     * @return string
     */

    public function timeIn()
    {
        return $this->get('time_in');
    }

    /** 
     * Destroy user session.
     * 
     * @return  void
     */

    public function destroy()
    {
        $this->cookie->delete();
        $this->session->delete();
        $this->permission->delete();
    }

}