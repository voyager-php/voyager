<?php

namespace Voyager\Database\DataType;

use Voyager\Database\ColumnBuilder;
use Voyager\Database\DataType;
use Voyager\Util\Str;

class Integer extends DataType
{
    /**
     * Set integer data properties.
     * 
     * @return  void
     */

    protected function setArgument()
    {
        $this->set('primary_key', false);
        $this->set('length', $this->argument);
        $this->set('autoincrement', false);
        $this->set('unsigned', false);
        $this->set('not_null', false);
    }

    /**
     * Set column as the primary key of the table.
     * 
     * @return  $this
     */

    public function primaryKey()
    {
        return $this->set('primary_key', true);
    }

    /**
     * Each time new row is added in to the table, increment
     * the integer value of the next row.
     * 
     * @return  $this
     */

    public function autoIncrement()
    {
        return $this->set('autoincrement', true);
    }

    /**
     * Numeric value will always be positive.
     * 
     * @return  $this
     */

    public function unsigned()
    {
        return $this->set('unsigned', true);
    }

    /**
     * Generate column SQL template;
     * 
     * @return  string
     */

    public function generate()
    {
        $sql = new Str();
        $sql->append('`' . $this->key . '`')
            ->append(' INT');

        if(!is_null($this->length))
        {
            $sql->append('(' . $this->length . ')');
        }

        if($this->unsigned)
        {
            $sql->append(' UNSIGNED');
        }

        if($this->not_null)
        {
            $sql->append(' NOT NULL');
        }

        if($this->autoincrement)
        {
            $sql->append(' AUTO_INCREMENT');
        }

        if($this->primary_key)
        {
            $sql->append(' PRIMARY KEY');
        }

        return $sql->get();
    }

}