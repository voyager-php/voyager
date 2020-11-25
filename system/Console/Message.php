<?php

namespace Voyager\Console;

use Voyager\Facade\Str;
use Voyager\Util\Arr;
use Voyager\Util\Str as Builder;

class Message
{
    /**
     * Store command-line text colors.
     * 
     * @var array
     */

    private $colors = [
        'black'             => '0;30',
        'dark-gray'         => '1;30',
        'red'               => '0;31',
        'light-red'         => '1;31',
        'green'             => '0;32',
        'light-green'       => '1;32',
        'brown'             => '0;33',
        'yellow'            => '1;33',
        'blue'              => '0;34',
        'light-blue'        => '1;34',
        'magenta'           => '0;35',
        'light-magenta'     => '1;35',
        'cyan'              => '0;36',
        'light-cyan'        => '1;36',
        'light-gray'        => '0;37',
        'white'             => '1;37',
    ];

    /**
     * Create new IO instance.
     * 
     * @return  void
     */

    public function __construct() {}

    /**
     * Create new message output.
     * 
     * @param   string $message
     * @param   string $color
     * @return  $this
     */

    public function set(string $message, string $color = null)
    {
        $colors = new Arr($this->colors);
        $builder = new Builder();

        if(!is_null($color) && $colors->hasKey($color))
        {
            $builder->append('{' . $color)
                    ->append('}' . $message)
                    ->append('{/' . $color)
                    ->append('}')
                    ->br();
        }
        else
        {
            $builder->append($message)
                    ->br();
        }

        echo $this->parseColor($builder->get());

        return $this;
    }

    /**
     * Print line-breaks.
     * 
     * @return $this
     */

    public function lineBreak()
    {
        return $this->set('');
    }

    /**
     * Print success message.
     * 
     * @param   string $message
     * @return  $this
     */

    public function success(string $message)
    {
        return $this->set($message, 'green');
    }

    /**
     * Print error message.
     * 
     * @param   string $message
     * @return  $this
     */

    public function error(string $message)
    {
        return $this->set($message, 'red');
    }

    /**
     * Parse color from message string.
     * 
     * @param   string $message
     * @return  string
     */

    private function parseColor(string $message)
    {
        $str = new Builder($message);

        while($str->has('{') && $str->has('{/') && $str->has('}'))
        {
            $pos = $str->pos('{');
            $tag = Str::moveFromStart($str->substr($pos, $str->pos('}') - $pos), '{');
            $close = '{/' . $tag . '}';
            $content = Str::break($str->substr($pos + strlen($tag) + 2, $str->length()), $close)[0];
            $color = "\e[" . $this->colors[$tag] . "m";

            $string = new Builder($str->substr(0, $pos));
            $string->append($color)
                   ->append($content)
                   ->append("\e[0m")
                   ->append($str->substr(strpos($message, $close) + strlen($close), $str->length()));

            $str->set($string->get());
        }

        return $str->get();
    }

}