<?php

namespace Voyager\Database\DataType;

use Voyager\Database\DataType;
use Voyager\Util\Str;

class Boolean extends DataType
{
    /**
     * Set data type arguments.
     * 
     * @return  void
     */

    protected function setArgument()
    {
        $this->set('default', null);
        $this->set('not_null', true);
    }

    /**
     * Set column default value.
     * 
     * @param   bool $default
     * @return  $this
     */

    public function default(bool $default)
    { 
        $this->set('default', $default);

        return $this;
    }
 
    /**
     * Generate column SQL template.
     * 
     * @return  string
     */

    public function generate()
    {
        $sql = new Str();
        $sql->append('`' . $this->key . '`')
            ->append(' BOOLEAN');
        
        if($this->not_null)
        {
            $sql->append(' NOT NULL');
        }

        if(!is_null($this->default))
        {
            $sql->append(' DEFAULT ')->append($this->default ? 'TRUE' : 'FALSE');
        }

        return $sql->get();
    }

}