<?php

namespace Voyager\Database\Operations;

use Voyager\Database\DB;
use Voyager\Database\DBWhereBuilder;
use Voyager\Util\Arr;

class UpdateOperation extends DBWhereBuilder
{
    /**
     * Store columns to update.
     * 
     * @var \Voyager\Util\Arr
     */

    private $toUpdate;

    /**
     * Set default fields to update.
     * 
     * @return  void
     */

    protected function setArgument()
    {
        $this->toUpdate = new Arr();

        if(is_array($this->arguments))
        {
            foreach($this->arguments as $key => $value)
            {
                $this->set($key, $value);
            }
        }
    }

    /**
     * Set new column value.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function set(string $key, $value)
    {
        $this->toUpdate->set($key, $value);

        return $this;
    }

    /**
     * Set column value to null.
     * 
     * @param   string $key
     * @return  $this
     */

    public function setToNull(string $key)
    {
        return $this->set($key, 'null');
    }

    /**
     * Generate sql query string.
     * 
     * @return  string
     */

    public function sql()
    {
        $where = $this->generateWhere();
        $sql = $this->sql->append('UPDATE ');
        $sql->append($this->tablename)
            ->append(' SET ');

        foreach($this->toUpdate->get() as $key => $value)
        {
            if(strtolower($value) === 'now()')
            {
                $sql->append($key)
                    ->append(' = NOW(), ');
            }
            else if(strtolower($value) === 'null')
            {
                $sql->append($key)
                    ->append(' = NULL, ');
            }
            else
            {
                $sql->append($key)
                    ->append(' = \'')
                    ->append(DB::escape($value))
                    ->append('\', ');
            }
        }

        if($sql->endWith(', '))
        {
            $sql->move(0, 2);
        }

        if(!is_null($where))
        {
            $sql->append(' WHERE ')->append($where);
        }

        return $sql->moveFromEnd(' ')->get();
    }

    /**
     * Execute update sql query.
     * 
     * @return  mixed
     */

    public function exec()
    {
        return DB::query($this->sql());
    }

}