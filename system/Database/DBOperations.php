<?php

namespace Voyager\Database;

use Voyager\Util\Arr;
use Voyager\Util\Str;

abstract class DBOperations
{
    /**
     * Name of the table.
     * 
     * @var string
     */

    protected $tablename;

    /**
     * Operation arguments.
     * 
     * @var mixed
     */

    protected $arguments;

    /**
     * Store join statements.
     * 
     * @var \Voyager\Util\Arr
     */

    protected $join;

    /**
     * Store where statements.
     * 
     * @var \Voyager\Util\Arr
     */

    protected $where;

    /**
     * Operation data.
     * 
     * @var array
     */

    protected $data;

    /**
     * DB operation properties.
     * 
     * @var \Voyager\Util\Arr
     */

    protected $prop;

    /**
     * SQL query string.
     * 
     * @var \Voyager\Util\Str
     */

    protected $sql;

    /**
     * Create new db operation instance.
     * 
     * @param   string $tablename
     * @param   mixed $arguments
     * @return  void
     */

    public function __construct(string $tablename, $arguments)
    {
        $this->sql = new Str();
        $this->tablename = strtolower($tablename);
        $this->arguments = $arguments;
        $this->prop = new Arr($this->data ?? []);
        $this->join = new Arr();
        $this->where = new Arr();
        $this->setArgument();
    }

    /**
     * Set operation data.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    protected function set(string $key, $value)
    {
        $this->prop->set($key, $value);
        return $this;
    }

    /**
     * Return SQL query string.
     * 
     * @return  string
     */

    public function sql()
    {
        return trim($this->sql);
    }

    /**
     * Set argument passed from DBOperation class.
     * 
     * @return  void
     */

    protected function setArgument() {}

}