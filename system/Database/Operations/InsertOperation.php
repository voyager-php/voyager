<?php

namespace Voyager\Database\Operations;

use Voyager\Database\DB;
use Voyager\Database\DBOperations;
use Voyager\Util\Arr;

class InsertOperation extends DBOperations
{
    /**
     * Store data to insert.
     * 
     * @var \Voyager\Util\Arr
     */

    private $toInsert;

    /**
     * Add argument as insert data.
     * 
     * @return  void
     */

    protected function setArgument()
    {
        $this->toInsert = new Arr();

        if(is_array($this->arguments))
        {
            $this->add($this->arguments);
        }
    }

    /**
     * Add set of data to insert to the table.
     * 
     * @param   array $data
     * @return  $this
     */

    public function add(array $data)
    {
        $this->toInsert->push($data);
        return $this;
    }

    /**
     * Execute insert operation query.
     * 
     * @return string
     */

    public function exec()
    {
        $sql = $this->sql;

        $this->toInsert->each(function($key, $data) use ($sql) {
            
            $sql->append('INSERT INTO ')
                ->append($this->tablename)
                ->append(' (`' . implode('`, `', array_keys($data)))
                ->append('`) VALUES(');

            foreach($data as $value)
            {
                $sql->append(" ")
                    ->append(DB::escape($value))
                    ->append(",");
            }

            $sql->move(0, 1)->append(')');

            DB::query($sql->moveFromEnd(' ')->get());
        });
    }

}