<?php

namespace Voyager\Database\DataType;

use Voyager\Database\DataType;
use Voyager\Util\Str;

class Date extends DataType
{
    /**
     * Set date data properties.
     * 
     * @return  void
     */
    
    protected function setArgument()
    {
        $this->set('current_timestamp', false);
        $this->set('not_null', false);
        $this->set('default', null);
    }

    /**
     * Set default as current timestamp.
     * 
     * @return  $this
     */

    public function currentTimestamp()
    {
        return $this->set('current_timestamp', true);
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
            ->append(' DATE');

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