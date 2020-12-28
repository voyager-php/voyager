<?php

namespace Voyager\Database;

abstract class Service
{
    /**
     * Store model object.
     * 
     * @var mixed
     */

    private $model;

    /**
     * Create new service instance.
     * 
     * @param   \Voyager\Database\Model
     * @return  void
     */

    public function __construct(Model $model)
    {
        $this->model = $model->model();
    }

    /**
     * Return table name.
     * 
     * @return  string
     */

    public function tablename()
    {
        return $this->model->tablename();
    }

    /**
     * Return table operation instance.
     * 
     * @return  \Voyager\Database\DBOperation
     */

    public function table()
    {
        return DB::table($this->tablename());
    }

    /**
     * Check if table exists.
     * 
     * @return  bool
     */

    public function exists()
    {
        return $this->model->exists();
    }

    /**
     * Return list of columns from the table.
     * 
     * @return  array
     */

    public function columns()
    {
        return $this->model->columns();
    }

    /**
     * Delete everything in thing table.
     * 
     * @return  void
     */

    public function truncate()
    {
        if(!$this->empty())
        {
            $this->model->truncate();
        }
    }

    /**
     * Execute an SQL select query.
     * 
     * @param   mixed $arg
     * @return  \Voyager\Database\Operations\SelectOperation
     */

    public function select($arg = '*')
    {
        return $this->model->select($arg);
    }

    /**
     * Execute an SQL update query.
     * 
     * @param   mixed $arg
     * @return  \Voyager\Database\Operations\UpdateOperation
     */

    public function update($arg = null)
    {
        return $this->model->update($arg);
    }

    /**
     * Execute an SQL insert query.
     * 
     * @param   mixed $arg
     * @return  \Voyager\Database\Operations\InsertOperation
     */

    public function insert($arg = null)
    {
        return $this->model->insert($arg);
    }

    /**
     * Execute an SQL delete query.
     * 
     * @return  \Voyager\Database\Operations\DeleteOperation
     */

    public function delete()
    {
        return $this->model->delete();
    }

    /**
     * Return all rows from the database.
     * 
     * @param   mixed $arg
     * @return  mixed
     */

    public function all($arg = '*')
    {
        return $this->select($arg)->get();
    }

    /**
     * Reset the total rows from the table.
     * 
     * @return  int
     */

    public function totalRows()
    {
        return $this->model->totalRows();
    }

    /**
     * Return true if table has no content.
     * 
     * @return  bool
     */

    public function empty()
    {
        return $this->totalRows() === 0;
    }

    /**
     * Return list of values of a column.
     * 
     * @param   string $key
     * @param   int $limit
     * @return  array
     */

    public function pluck(string $key, int $limit = null)
    {
        if(is_null($limit))
        {
            return $this->select($key)->get()->fetch();
        }
        else
        {
            return $this->select($key)->limit(0, $limit)->get()->fetch();
        }
    }

    /**
     * Return true if row exist.
     * 
     * @param   int $id
     * @return  bool
     */

    public function exist(int $id)
    {
        return !$this->select('id')->equal('id', $id)->get()->empty();
    }

    /**
     * Return row data by id.
     * 
     * @param   int $id
     * @param   mixed $arg
     * @return  array
     */

    public function getById(int $id, $arg = '*')
    {
        return $this->select($arg)->equal('id', $id)->get();
    }

    /**
     * Set value for a specific key using id.
     * 
     * @param   int $id
     * @param   string $key
     * @param   mixed $value
     * @return  mixed
     */

    public function setById(int $id, string $key, $value)
    {
        return $this->update()->set($key, $value)->equal('id', $id)->exec();
    }

    /**
     * Execute an SQL update query with specific id.
     * 
     * @param   int $id
     * @param   array $data
     * @return  mixed
     */

    public function editById(int $id, $data = [])
    {
        return $this->update($data)->equal('id', $id)->exec();
    }

    /**
     * Execute an SQL delete query with specific id.
     * 
     * @param   int $id
     * @return  mixed
     */

    public function deleteById(int $id)
    {
        return $this->delete()->equal('id', $id)->exec();
    }

    /**
     * Return all result fields equal with the value.
     * 
     * @param   string $key
     * @param   mixed $value
     * @param   mixed $arg
     * @return  \Voyager\Database\Operations\SelectOperation
     */

    public function equal(string $key, $value, $arg = '*')
    {
        return $this->select($arg)->equal($key, $value);
    }

    /**
     * Return all result fields that are not equal with the value.
     * 
     * @param   string $key
     * @param   mixed $value
     * @param   mixed $arg
     * @return  \Voyager\Database\Operations\SelectOperation
     */

    public function notEqual(string $key, $value, $arg = '*')
    {
        return $this->select($arg)->notEqual($key, $value);
    }

    /**
     * Return the number of result in a query.
     * 
     * @param   string $alias
     * @return  \Voyager\Database\Operations\SelectOperation
     */

    public function count(string $alias)
    {
        return $this->select('COUNT(id) AS ' . $alias);
    }

    /**
     * Return true if field has the following value.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  bool
     */

    public function has(string $key, $value)
    {
        return (int)$this->count('total')->equal($key, $value)->get()->total !== 0;
    }

    /**
     * Return data by index number.
     * 
     * @param   int $index
     * @param   mixed $arg
     * @return  mixed
     */

    public function get(int $index, $arg = '*')
    {
        return $this->select($arg)->limit($index, 1)->get();
    }

    /**
     * Return the very first row data from the table.
     * 
     * @param   mixed $arg
     * @return  mixed
     */

    public function first($arg = '*')
    {
        return $this->get(0, $arg);
    }

    /**
     * Return the very last row data from the table.
     * 
     * @param   mixed $arg
     * @return  mixed
     */

    public function last($arg = '*')
    {
        return $this->select($arg)->orderBy('desc', 'id')->limit(0, 1)->get();
    }

    /**
     * Sum the values of integer columns.
     * 
     * @param   string $key
     * @return  int
     */

    public function sum(string $key)
    {
        return (int)$this->select('SUM(' . $key . ') AS total_sum')->get()->total_sum;
    }

    /**
     * Compute the average value of integer column.
     * 
     * @param   string $key
     * @return  int
     */

    public function ave(string $key)
    {
        return (int)$this->select('AVE(' . $key . ') AS total_ave')->get()->total_ave;
    }

    /**
     * Return the minimum value from an integer column.
     * 
     * @param   string $key
     * @return  int
     */

    public function min(string $key)
    {
        return (int)$this->select('MIN(' . $key . ') AS min_value')->get()->min_value;
    }

    /**
     * Return the maximum value from an integer column.
     * 
     * @param   string $key
     * @return  int
     */

    public function max(string $key)
    {
        return (int)$this->select('MAX(' . $key . ') AS max_value')->get()->max_value;
    }

    /**
     * Marked row as deleted without deleting
     * the physical row.
     * 
     * @param   int $id
     * @return  mixed
     */

    public function softDelete(int $id)
    {
        return $this->setById($id, 'deleted', 'NOW()');
    }

    /**
     * Return true if row is marked as soft deleted.
     * 
     * @param   int $id
     * @return  bool
     */

    public function isSoftDeleted(int $id)
    {
        return (bool)!$this->select('id')->equal('id', $id)->isDeleted()->get()->empty();
    }

    /**
     * Return true if row is already edited.
     * 
     * @param   int $id
     * @return  bool
     */

    public function isEdited(int $id)
    {
        return $this->select('id')->equal('id', $id)->isDeleted()->get()->empty();
    }

    /**
     * Check if service method exist then call.
     * 
     * @param   string $method
     * @param   array $arg
     * @return  $this
     */

    public function call(string $method, array $arg = [])
    {
        if(method_exists($this, $method))
        {
            return $this->{$method}(...$arg);
        }

        return $this;
    }

}