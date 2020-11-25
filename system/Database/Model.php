<?php

namespace Voyager\Database;

use Voyager\Facade\Arr;
use Voyager\Facade\Str;

abstract class Model
{
    /**
     * Name of database table.
     * 
     * @var string
     */

    private $tablename;

    /**
     * Service instance object.
     * 
     * @var \Voyager\Database\Service
     */

    private $service;

    /**
     * Table instance object.
     * 
     * @var \Voyager\Database\DBOperation
     */

    private $table;

    /**
     * Create new model instance.
     * 
     * @param   string $class
     * @return  void
     */

    public function __construct(string $class)
    {
        $classname = Arr::last(explode('\\', $class));
        $this->tablename = Str::toKebabCase($classname);
        $service = 'App\Database\Service\\' . $classname;
        $this->table = DB::table($this->tablename);
        $this->service = new $service($this);
    }

    /**
     * Return model table name.
     * 
     * @return  string
     */

    public function tablename()
    {
        return $this->tablename;
    }

    /**
     * Return table model.
     * 
     * @return  \Voyager\Database\DBOperation
     */

    public function model()
    {
        return $this->table;
    }

    /**
     * Return service instance object.
     * 
     * @return  \Voyager\Database\Service
     */

    public function service()
    {
        return $this->service;
    }

    /**
     * Call service method.
     * 
     * @param   string $key
     * @param   array $arg
     * @return  mixed
     */

    public function call(string $key, array $arg)
    {
        return $this->service->call($key, $arg);
    }

    /**
     * Override from the parent class.
     * 
     * @param   \Voyager\Database\Schema
     * @return  void
     */

    protected static function migrate(Schema $schema) {}

    /**
     * Use model as facade to call service methods.
     * 
     * @param   string $method
     * @param   array $arguments
     * @return  mixed
     */

    public static function __callStatic(string $method, $arguments)
    {
        $model = static::class;
        $instance = new $model($model);
        
        return $instance->call($method, $arguments);
    }

    /**
     * Called by the CLI to create database tables.
     * 
     * @return  mixed 
     */

    public static function migration()
    {
        $model = static::class;
        $instance = new $model($model);
        
        static::migrate(new Schema($instance->tablename()));

        return DB::query(Schema::get()->generate());
    }

    /**
     * Return the schematic of the model.
     * 
     * @return  \Voyager\Util\Arr
     */

    public static function columns()
    {
        $model = static::class;
        $instance = new $model($model);
        
        static::migrate(new Schema($instance->tablename()));

        return Schema::get()->columns();
    }

}