<?php

namespace Voyager\Database\Operations;

use Voyager\Database\DB;
use Voyager\Database\DBWhereBuilder;

class DeleteOperation extends DBWhereBuilder
{
    /**
     * Generate sql query string.
     * 
     * @return  string
     */

    public function sql()
    {
        $where = $this->generateWhere();
        $sql = $this->sql->append('DELETE FROM ');
        $sql->append($this->tablename);

        if(!is_null($where))
        {
            $sql->append(' WHERE ')->append($where);
        }

        return $sql->get();
    }

    /**
     * Execute delete query.
     * 
     * @return  mixed
     */

    public function exec()
    {
        return DB::query($this->sql());
    }

}