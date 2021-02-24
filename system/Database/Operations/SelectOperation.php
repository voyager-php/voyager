<?php

namespace Voyager\Database\Operations;

use Voyager\Database\DB;
use Voyager\Database\DBWhereBuilder;

class SelectOperation extends DBWhereBuilder
{
    /**
     * Operation data.
     * 
     * @var array
     */

    protected $data = [
        'operation'         => 'select',
        'retrieve'          => '*',
        'distinct'          => false,
        'order_dir'         => 'desc',
        'order_by'          => null,
        'limit_start'       => 0,
        'limit_length'      => null,
    ];

    /**
     * Set fields to retrieve.
     * 
     * @return  void
     */
    
    protected function setArgument()
    {
        $arguments = $this->arguments;

        if(is_string($arguments))
        {
            $this->set('retrieve', $arguments);
        }
        else if(is_array($arguments))
        {
            $this->set('retrieve', implode(', ', $arguments));
        }
    }
 
    /**
     * Set distinct statement to return result with distinct values.
     * 
     * @return  $this
     */

    public function distinct()
    {
        return $this->set('distinct', true);
    }

    /**
     * Inner join current table to another table.
     * 
     * @param   string $tablename
     * @param   array $columns
     * @param   string $operator
     * @return  $this
     */

    public function innerJoin(string $tablename, array $columns, string $operator = '=')
    {
        $this->join->push([
            'type'              => 'inner',
            'tablename'         => $tablename,
            'columns'           => $columns,
            'operator'          => $operator,
        ]);

        return $this;
    }

    /**
     * Left join current table with another table.
     * 
     * @param   string $tablename
     * @param   array $columns
     * @param   string $operator
     * @return  $this
     */

    public function leftJoin(string $tablename, array $columns, string $operator = '=')
    {
        $this->join->push([
            'type'              => 'left',
            'tablename'         => $tablename,
            'columns'           => $columns,
            'operator'          => $operator,
        ]);

        return $this;
    }

    /**
     * Right join current table with another table.
     * 
     * @param   string $tablename
     * @param   array $columns
     * @param   string $operator
     * @return  $this
     */

    public function rightJoin(string $tablename, array $columns, string $operator = '=')
    {
        $this->join->push([
            'type'              => 'right',
            'tablename'         => $tablename,
            'columns'           => $columns,
            'operator'          => $operator,
        ]);

        return $this;
    }

    /**
     * Set order by clause.
     * 
     * @param   string $direction
     * @param   mixed $fields
     * @return  $this
     */

    public function orderBy(string $direction, $fields)
    {
        if(is_string($fields))
        {
            $this->set('order_by', $fields);
        }
        else if(is_array($fields))
        {
            $this->set('order_by', implode(', ', $fields));
        }

        return $this->set('order_dir', $direction);
    }

    /**
     * Sort results in ascending order.
     * 
     * @param   mixed $fields
     * @return  $this
     */

    public function asc($fields = 'created')
    {
        return $this->orderBy('asc', $fields);
    }

    /**
     * Sort results in descending order.
     * 
     * @param   mixed $fields
     * @return  $this
     */

    public function desc($fields = 'created')
    {
        return $this->orderBy('desc', $fields);
    }

    /**
     * Set result order to random.
     * 
     * @return  $this
     */

    public function random()
    {
        return $this->set('order_dir', 'rand');
    }

    /**
     * Set result limit clause.
     * 
     * @param   int $start
     * @param   int $length
     * @return  $this
     */

    public function limit(int $start, int $length = 1)
    {
        return $this->set('limit_start', $start)
                    ->set('limit_length', $length);
    }

    /**
     * Generate and return sql query.
     * 
     * @return  string
     */

    public function sql()
    {
        $sql = $this->sql->set('')->append('SELECT ');
        
        if($this->prop->get('distinct'))
        {
            $sql->append('DISTINCT ');
        }

        $sql->append($this->prop->get('retrieve'))
            ->append(' FROM ')
            ->append($this->tablename);

        if(!$this->join->empty())
        {
            foreach($this->join->get() as $join)
            {
                $sql->append(' ' . strtoupper($join['type']))
                    ->append(' JOIN')
                    ->append(' ' . $join['tablename'])
                    ->append(' ON');

                foreach($join['columns'] as $key1 => $key2)
                {
                    $sql->append(' ' . $this->tablename . '.' . $key1)
                        ->append(' ' . $join['operator'] . ' ')
                        ->append($join['tablename'] . '.' . $key2)
                        ->append(' AND ');
                }

                if($sql->endWith(' AND '))
                {
                    $sql->move(0, 5);
                }
            }
        }

        $where = $this->generateWhere();
        
        if(!is_null($where))
        {
            $sql->append(' WHERE ')->append($where);
        }

        if($this->prop->get('order_dir') === 'rand')
        {
            $sql->append(' ORDER BY RAND()');
        }
        else
        {
            if(!is_null($this->prop->get('order_by')))
            {
                $sql->append(' ORDER BY ')
                    ->append($this->prop->get('order_by'))
                    ->append(' ')
                    ->append(strtoupper($this->prop->get('order_dir')));
            }
        }

        if(!is_null($this->prop->get('limit_length')))
        {
            $sql->append(' LIMIT ')
                ->append($this->prop->get('limit_start'))
                ->append(', ')
                ->append($this->prop->get('limit_length'));
        }

        return $sql->moveFromEnd(' ')->get();
    }

    /**
     * Return database query object.
     * 
     * @return  mixed
     */

    public function get()
    {
        return DB::query($this->sql());
    }

    /**
     * Get the first result.
     * 
     * @return  \Voyager\Util\Data\Collection
     */

    public function first()
    {
        return $this->get()->first();
    }

    /**
     * Get the very last result.
     * 
     * @return  \Voyager\Util\Data\Collection
     */

    public function last()
    {
        return $this->get()->last();
    }

}