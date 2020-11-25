<?php

namespace Voyager\Database;

use Voyager\Database\Operations\DeleteOperation;
use Voyager\Database\Operations\InsertOperation;
use Voyager\Database\Operations\SelectOperation;
use Voyager\Database\Operations\UpdateOperation;
use Voyager\Util\Arr;

class DBOperation
{
    /**
     * Database table name.
     * 
     * @var string
     */

    private $tablename;

    /**
     * Create new database operation instance.
     * 
     * @param   string $tablename
     * @return  void
     */

    public function __construct(string $tablename)
    {
        $this->tablename = $tablename;
    }

    /**
     * Return table name.
     * 
     * @return  string
     */

    public function tablename()
    {
        return $this->tablename;
    }

    /**
     * Return true if table exist.
     * 
     * @return  bool
     */

    public function exists()
    {
        return DB::query('DESCRIBE ' . $this->tablename)->success();
    }

    /**
     * Return table columns.
     * 
     * @return  array
     */

    public function columns()
    {
        $field = new Arr();
        $query = DB::query('SHOW COLUMNS FROM ' . $this->tablename);
    
        foreach($query->fetch()->toArray() as $column)
        {
            $field->push($column['Field']);
        }

        return $field->get();
    }

    /**
     * Reset the total number of rows in the table.
     * 
     * @return  int
     */

    public function totalRows()
    {
        return (int)$this->select('COUNT(*) AS total_count')->get()->first()->total_count;
    }

    /**
     * Reset the integer autoincrement to 1.
     * 
     * @return mixed
     */

    public function resetAutoIncrement()
    {
        return DB::query('ALTER TABLE ' . $this->tablename . ' AUTO_INCREMENT = 1');
    }

    /**
     * Empty the rows of the table.
     * 
     * @return mixed
     */

    public function truncate()
    {
        $query = DB::query('TRUNCATE TABLE ' . $this->tablename);
        $this->resetAutoIncrement();

        return $query;
    }

    /**
     * Return select operation instance.
     * 
     * @param   mixed $argument
     * @return  \Voyager\Database\Operations\SelectOperation
     */

    public function select($argument)
    {
        return new SelectOperation($this->tablename, $argument);
    }

    /**
     * Return update operation instance.
     * 
     * @param   array $argument
     * @return  \Voyager\Database\Operations\UpdateOperation
     */

    public function update(array $argument = null)
    {
        return new UpdateOperation($this->tablename, $argument);
    }

    /**
     * Return insert operation instance.
     * 
     * @param   array $argument
     * @return  \Voyager\Database\Operations\InsertOperation
     */

    public function insert(array $argument = null)
    {
        return new InsertOperation($this->tablename, $argument);
    }

    /**
     * Return delete operation instance.
     * 
     * @return  \Voyager\Database\Operations\DeleteOperation
     */

    public function delete()
    {
        return new DeleteOperation($this->tablename, null);
    }

    /**
     * Drop the table or column from the database.
     * 
     * @param   string $name
     * @return  mixed
     */

    public function drop(string $name = null)
    {
        if(!is_null($name))
        {
            return DB::query('ALTER TABLE ' . $this->tablename . ' DROP COLUMN ' . $name);
        }
        else
        {
            return DB::query('DROP TABLE ' . $this->tablename);
        }
    }

}