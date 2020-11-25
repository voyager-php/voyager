<?php

namespace Voyager\Database\DataType;

use Voyager\Database\DataType;
use Voyager\Util\Str;

class Datetime extends DataType
{
    /**
     * Set datetime data properties.
     * 
     * @return  void
     */

    protected function setArgument()
    {
        $this->set('current_timestamp', false);
        $this->set('on_update_current', false);
        $this->set('not_null', false);
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
     * Update datetime every time row is updated.
     * 
     * @return  void
     */

    public function onUpdate()
    {
        return $this->set('on_update_current', true);
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
            ->append(' DATETIME');

        if($this->not_null)
        {
            $sql->append(' NOT NULL');
        }
        
        if($this->current_timestamp)
        {
            $sql->append(' DEFAULT CURRENT_TIMESTAMP');
        }

        if($this->on_update_current)
        {
            $sql->append(' ON UPDATE CURRENT_TIMESTAMP');
        }

        return $sql->get();
    }

}