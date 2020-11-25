<?php

namespace Voyager\Http\Security;

use Voyager\Core\Config as Configuration;

class PermissionConfig extends Configuration
{
    /**
     * Instantiate config instance.
     * 
     * @param   mixed $argument
     * @return  $this
     */

    protected static function bind()
    {
        return new self('permission', 'config/permission.php');
    }

    /**
     * What kind of data to return?
     * 
     * @param   mixed $data
     * @return  mixed
     */

    protected static function send($data)
    {
        $data = [];

        foreach(Permission::get() as $permission)
        {
            $data[$permission->key()] = $permission->getPermissions();
        }

        return $data;
    }

}