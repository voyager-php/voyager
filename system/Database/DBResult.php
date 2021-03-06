<?php

namespace Voyager\Database;

use Voyager\App\Request;
use Voyager\Util\Data\Bundle;

class DBResult extends DBResponse
{
    /**
     * Store result in bundle objects.
     * 
     * @var \Voyager\Util\Data\Bundle
     */

    private $bundle;

    /**
     * Return result bundle.
     * 
     * @return  \Voyager\Util\Data\Bundle
     */

    public function fetch()
    {
        if(is_null($this->bundle))
        {
            $this->bundle = new Bundle($this->conn->fetch());
            $this->conn->freeResult();
        }

        return $this->bundle;
    }

    /**
     * Return the very first result.
     * 
     * @return  mixed
     */

    public function first()
    {
        $this->fetch();
        return $this->bundle->first();
    }

    /**
     * Return the very last result.
     * 
     * @return  mixed
     */

    public function last()
    {
        $this->fetch();
        return $this->bundle->last();
    }

    /**
     * Return row by index number.
     * 
     * @param   int $index
     * @return  mixed
     */

    public function get(int $index)
    {
        if($index <= $this->numRows())
        {
            $this->fetch();
            return $this->bundle->get($index);
        }
    }

    /**
     * Return total number of rows.
     * 
     * @return  int
     */

    public function numRows()
    {
        $this->fetch();
        return (int)$this->bundle->size();
    }

    /**
     * Return true if result has now rows.
     * 
     * @return  bool
     */

    public function empty()
    {
        return $this->numRows() === 0;
    }

    /**
     * Return result as array.
     * 
     * @return  array
     */

    public function toArray()
    {
        $this->fetch();
        return $this->bundle->toArray();
    }

    /**
     * Return json encoded result.
     * 
     * @return  string
     */

    public function toJson()
    {
        $this->fetch();
        return $this->bundle->toJson();
    }

    /**
     * Transform database result.
     * 
     * @param   string $class
     * @return  $this
     */

    public function transform(string $class)
    {
        $this->bundle = $this->fetch()->transform($class);
    
        return $this;
    }

    /**
     * Add new column to result.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function addColumn(string $key, $value)
    {
        $this->fetch();
        $this->bundle->addColumn($key, $value);

        return $this;
    }

    /**
     * Return list of value of a column.
     * 
     * @param   string $key
     * @return  array
     */

    public function column(string $key)
    {
        $this->fetch();
        return $this->bundle->column($key);
    }

    /**
     * If result is just one row, automatically get
     * values by calling it as property.
     * 
     * @param   string $name
     * @return  mixed
     */

    public function __get(string $name)
    {
        if($this->numRows() === 1)
        {
            $data = $this->first();

            if($data->has($name))
            {
                return $data->{$name};
            }
        }
    }

    /**
     * Paginate the results from database.
     * 
     * @param   \Voyager\App\Request $request
     * @return  \Voyager\Util\Data\Collection
     */

    public function paginate(Request $request)
    {
        $maximum = 5;
        $data = $this->fetch();
        $total = (int) $this->numRows();
        $page = (int) $request->get('page', 1);
        $per_page = (int) $request->get('per_page', 10);
        $total_page = (int) ceil($total / $per_page);
        $start = ($page * $per_page) - $per_page;
        $end = $start + $per_page;
        $result = [];
        $min = $page;
        $max = ($min - 1) + $maximum;

        if($max > $total_page)
        {
            $max = $total_page;
        }

        $diff = ($max - $min) + 1;

        if($diff < $maximum)
        {
            $min -= ($maximum - $diff);
        }

        if($min <= 0)
        {
            $min = 1;
        }

        for($i = $start + 1; $i <= $end; $i++)
        {
            if($i <= $total)
            {
                $result[] = $data->get($i - 1)->toArray();
            }
        }

        if($total === 0)
        {
            app()->code = 404;
        }

        return $request->apiResponse($result, [
            'total'             => $total,
            'paginate'          => true,
            'page'              => $page,
            'per_page'          => $per_page,
            'total_page'        => $total_page,
            'rows'              => sizeof($result),
            'pages'             => [
                'min'           => $min,
                'max'           => $max,
            ],
        ]);
    }

}