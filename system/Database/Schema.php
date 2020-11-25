<?php

namespace Voyager\Database;

use Voyager\Database\DataType\Boolean;
use Voyager\Database\DataType\Date;
use Voyager\Database\DataType\Datetime;
use Voyager\Database\DataType\Integer;
use Voyager\Database\DataType\Text;
use Voyager\Database\DataType\Time;
use Voyager\Util\Arr;
use Voyager\Util\Str as Builder;

class Schema
{
    /**
     * Current schema instance.
     * 
     * @var \Voyager\Database\Schema
     */

    private static $register;

    /**
     * Store columns data.
     * 
     * @var \Voyager\Util\Arr
     */

    private $columns;

    /**
     * Store the table name.
     * 
     * @var string
     */

    private $tablename;

    /**
     * Store database engine to use.
     * 
     * @var string
     */

    private $engine;

    /**
     * Create new schema instance.
     * 
     * @param   string $tablename
     * @return  void
     */

    public function __construct(string $tablename)
    {
        $this->columns = new Arr();
        $this->tablename = $tablename;
        $this->integer('id')->autoIncrement()->primaryKey();
        static::$register = $this;
    }

    /**
     * Return current schema instance.
     * 
     * @return  $this
     */

    public static function get()
    {
        $instance = static::$register;
        $instance->datetime('updated')->onUpdate();
        $instance->datetime('deleted');
        $instance->datetime('created')->notNull()->currentTimestamp();

        return $instance;
    }

    /**
     * Return registered schemas.
     * 
     * @return  \Voyager\Util\Arr
     */

    public function columns()
    {
        return $this->columns;
    }

    /**
     * Set storage engine.
     * 
     * @param   string $engine
     * @return  $this
     */

    public function engine(string $engine)
    {
        $this->engine = $engine;

        return $this;
    }

    /**
     * Generate create table SQL query.
     * 
     * @return  string
     */

    public function generate()
    {
        $sql = new Builder('CREATE TABLE ');
        $sql->append($this->tablename)
            ->append(' (');

        foreach($this->columns->get() as $column)
        {
            $sql->append($column->generate() . ', ');
        }

        $sql->move(0, 2)
            ->append(')');

        if(!is_null($this->engine))
        {
            $sql->append(' ENGINE=' . $this->engine);
        }

        return $sql->get();
    }

    /**
     * Integer datatype can be numeric values like id and others.
     * 
     * @param   string $key
     * @param   int $length
     * @return  \Voyager\Database\DataType\Integer
     */

    public function integer(string $key, int $length = null)
    {
        return $this->columns->set($key, new Integer($key, $length))->get($key);
    }

    /**
     * Fields that can have a value either 0 or 1.
     * 
     * @param   string $key
     * @return  \Voyager\Database\DataType\Boolean
     */

    public function boolean(string $key)
    {
        return $this->columns->set($key, new Boolean($key))->get($key);
    }

    /**
     * Field with special or alphanumeric characters.
     * 
     * @param   string $key
     * @param   int $length
     * @return  \Voyager\Database\DataType\Text
     */

    public function text(string $key, int $length = null)
    {
        return $this->columns->set($key, new Text($key, $length))->get($key);
    }

    /**
     * Field that stores date.
     * 
     * @param   string $key
     * @return  \Voyager\Database\DataType\Date
     */

    public function date(string $key)
    {
        return $this->columns->set($key, new Date($key))->get($key);
    }

    /**
     * Field that stores time.
     * 
     * @param   string $key
     * @return  \Voyager\Database\DataType\Time
     */

    public function time(string $key)
    {
        return $this->columns->set($key, new Time($key))->get($key);
    }

    /**
     * Field that stores timestamp.
     * 
     * @param   string $key
     * @return  \Voyager\Database\DataType\Datetime
     */

    public function datetime(string $key)
    {
        return $this->columns->set($key, new Datetime($key))->get($key);
    }

}