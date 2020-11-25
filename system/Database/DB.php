<?php

namespace Voyager\Database;

use PDO;

class DB
{
    /**
     * Connection object.
     * 
     * @var object
     */

    private static $connection;

    /**
     * If connection is established.
     * 
     * @var bool
     */

    private static $connected = false;
    
    /**
     * Return true if connection is established.
     * 
     * @return  bool
     */

    private static function connect()
    {
        if(is_null(static::$connection))
        {
            $driver = static::driver();
            
            if(in_array($driver, PDO::getAvailableDrivers()))
            {
                $pdo = new PDOAdapter($driver . ':host=' . static::host() . ';dbname=' . static::database() . ';charset=' . static::charset() . ';port=' . static::port(), static::username(), static::password());
                
                if($pdo->connected())
                {
                    if(function_exists('app'))
                    {
                        app()->promise('database', function() {
                            DB::close();
                        });
                    }

                    static::$connection = $pdo;
                }
            }
        }
    }

    /**
     * Return connection instance.
     * 
     * @return  object
     */

    public static function connection()
    {
        static::connect();
        return static::$connection;
    }

    /**
     * Return true if connected.
     * 
     * @return  bool
     */

    public static function connected()
    {
        return static::$connected;        
    }

    /**
     * Return server host.
     * 
     * @return string
     */

    public static function host()
    {
        return env('DB_HOST');
    }

    /**
     * Return database server port.
     * 
     * @return string
     */

    public static function port() 
    {
        return env('DB_PORT');
    }

    /**
     * Return charset to use.
     * 
     * @return  string
     */

    public static function charset()
    {
        return env('DB_CHARSET');
    }

    /**
     * Return database name.
     * 
     * @return  string
     */

    public static function database()
    {
        return env('DB_DATABASE');
    }

    /**
     * Return database username credentials.
     * 
     * @return  string
     */

    public static function username()
    {
        return env('DB_USERNAME');
    }

    /**
     * Return database password credentials.
     * 
     * @return  string
     */

    public static function password()
    {
        return env('DB_PASSWORD');
    }

    /**
     * Return API driver to use.
     * 
     * @return  string
     */

    public static function driver()
    {
        return strtolower(env('DB_DRIVER'));
    }

    /**
     * Return new database instance object.
     * 
     * @param   string $tablename
     * @return  \Voyager\Database\DBOperation
     */

    public static function table(string $tablename)
    {
        return new DBOperation($tablename);
    }

    /**
     * Return list of tables in the database.
     * 
     * @return  array
     */

    public static function tables()
    {
        $tables = [];
        $query = DB::query('SHOW TABLES FROM ' . static::database());
        
        foreach($query->fetch()->toArray() as $key => $value)
        {
            $tables[] = $value['Tables_in_' . static::database()];
        }

        return $tables;
    }

    /**
     * Truncate table.
     * 
     * @param   string $tablename
     * @return  void
     */

    public static function truncate(string $tablename)
    {
        static::connect();
        return static::table($tablename)->truncate();
    }

    /**
     * Return select operation instance.
     * 
     * @param   string $tablename
     * @param   mixed $argument
     * @return  \Voyager\Database\Operations\SelectOperation
     */

    public static function select(string $tablename, $argument = '*')
    {
        static::connect();
        return static::table($tablename)->select($argument);
    }

    /**
     * Return update operation instance.
     * 
     * @param   string $tablename
     * @param   array $data
     * @return  \Voyager\Database\Operations\UpdateOperation
     */

    public static function update(string $tablename, array $data = [])
    {
        static::connect();
        return static::table($tablename)->update($data);
    }

    /**
     * Return insert operation instance.
     * 
     * @param   string $tablename
     * @param   array $data
     * @return  \Voyager\Database\Operations\InsertOperation
     */

    public static function insert(string $tablename, array $data = [])
    {
        static::connect();
        return static::table($tablename)->insert($data);
    }

    /**
     * Return delete operation instance.
     * 
     * @param   string $tablename
     * @param   array $data
     * @return  \Voyager\Database\Operations\DeleteOperation
     */

    public static function delete(string $tablename, array $data = [])
    {
        static::connect();
        return static::table($tablename)->delete($data);
    }

    /**
     * Execute new SQL query.
     * 
     * @param   string $string
     * @return  object
     */

    public static function query(string $string)
    {
        static::connect();
        return static::connection()->query($string);
    }

    /**
     * Free result from memory.
     * 
     * @return  void
     */

    public static function freeResult()
    {
        static::connect();
        return static::connection()->freeResult();
    }

    /**
     * Return escaped string to prevent SQL injection.
     * 
     * @param   string $string
     * @return  string
     */

    public static function escape(string $string)
    {
        static::connect();
        return static::connection()->escape($string);
    }

    /**
     * Terminate mysqli connection.
     * 
     * @return  object
     */

    public static function close()
    {
        static::connect();
        return static::connection()->close();
    }

}