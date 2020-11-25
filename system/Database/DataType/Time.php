<?php

namespace Voyager\Database\DataType;

use Voyager\Database\DataType;
use Voyager\Util\Str;

class Time extends DataType
{
    /**
     * Set time data properties.
     * 
     * @return  void
     */

    protected function setArgument()
    {
        $this->set('current_timestamp', false);
        $this->set('not_null', false);
    }

    /**
     * Set default as current timestamp.
     * 
     * @return  $this
     */

    public function currentTimestamp()
    {
        return $this->set('current_timstamp', true);
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
            ->append(' TIME');

        if($this->not_null)
        {
            $sql->append(' NOT NULL');
        }
        
        if($this->current_timestamp)
        {
            $sql->append(' DEFAULT CURRENT_TIMESTAMP');
        }

        return $sql->get();
    }

}