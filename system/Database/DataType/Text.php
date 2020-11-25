<?php

namespace Voyager\Database\DataType;

use Voyager\Database\DataType;
use Voyager\Util\Str;

class Text extends DataType
{
    /**
     * Set text data properties.
     * 
     * @return  void
     */

    protected function setArgument()
    {
        $this->set('type', 'VARCHAR');
        $this->set('length', $this->argument);
        $this->set('not_null', false);
        $this->set('default', null);

        if(is_null($this->length))
        {
            $this->set('type', 'TEXT');
        }
    }

    /**
     * Set default value.
     * 
     * @param   string $default
     * @return  $this
     */

    public function default(string $default)
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
        $sql->append('`' . $this->key . '` ')
            ->append($this->type);

        if(!is_null($this->length))
        {
            $sql->append('(' . $this->length . ')');
        }

        if(!is_null($this->not_null))
        {
            $sql->append(' NOT NULL');
        }

        if(!is_null($this->default))
        {
            $sql->append(" DEFAULT '")
                ->append($this->default)
                ->append("'");
        }

        return $sql->get();
    }

}