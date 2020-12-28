<?php

namespace Voyager\Resource;

use Voyager\Util\Arr;
use Voyager\Util\Data\Collection;

abstract class Transformer
{
    /**
     * Store transformed array.
     * 
     * @var \Voyager\Util\Arr
     */

    private $output;

    /**
     * Create new transformer instance.
     * 
     * @param   array
     * @return  mixed
     */

    public function __construct(array $resource)
    {
        $this->output = new Arr();

        foreach($resource as $item)
        {
            if($item instanceof Collection)
            {
                $data = $this->transform($item);
                $this->output->push(new Collection($this->format($data)));
            }
        }
    }

    /**
     * Format transformed data in to proper data types.
     * 
     * @param   array $data
     * @return  array
     */

    private function format(array $data)
    {
        $res = [];

        foreach($data as $key => $value)
        {
            $res[$key] = $value;
        }

        return $res;
    }

    /**
     * Override from the parent class.
     * 
     * @param   \Voyager\Util\Data\Collection $data
     * @return  array
     */

    protected function transform(Collection $data) {}

    /**
     * Return transformed output.
     * 
     * @return  array
     */

    public function output()
    {
        return $this->output->get();
    }

}