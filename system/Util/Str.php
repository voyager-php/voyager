<?php

namespace Voyager\Util;

class Str
{
    /**
     * The string data.
     * 
     * @var string
     */

    protected $string;

    /**
     * Create a new string instance.
     * 
     * @param   mixed $string
     * @return  void
     */

    public function __construct($string = '')
    {
        if($string instanceof Str)
        {
            $string = $string->get();
        }

        $this->set($string);
    }

    /**
     * Return string.
     * 
     * @return string
     */

    public function get()
    {
        return $this->string;
    }

    /**
     * Set string value.
     * 
     * @param   string $string
     * @return  $this
     */

    public function set(string $string)
    {
        $this->string = $string;

        return $this;
    }

    /**
     * Return the length of the string.
     * 
     * @return int
     */

    public function length()
    {
        return (int)strlen($this->get());
    }

    /**
     * Return true if string is empty.
     * 
     * @return bool
     */

    public function empty()
    {
        return $this->get() === '' || empty($this->get()) || $this->length() === 0;
    }

    /**
     * Concatenate string at the end of the string.
     * 
     * @param   string $string
     * @return  $this
     */

    public function append(?string $string)
    {
        return $this->set($this->get() . $string);
    }

    /**
     * Concatenate string at the beginning of the string.
     * 
     * @param   string $string
     * @return  $this
     */

    public function prepend(?string $string)
    {
        return $this->set($string . $this->get());
    }

    /**
     * Convert string to lowercase letters.
     * 
     * @return $this
     */

    public function toLower()
    {
        return $this->set(strtolower($this->get()));
    }

    /**
     * Convert string to uppercase letters.
     * 
     * @return $this
     */

    public function toUpper()
    {
        return $this->set(strtoupper($this->get()));
    }

    /**
     * Explode string.
     * 
     * @param   string $delimeter
     * @return  array
     */

    public function explode(string $delimeter)
    {
        return explode($delimeter, $this->get());
    }

    /**
     * Split string.
     * 
     * @param   int $length
     * @return  array
     */

    public function split(int $length = 1)
    {
        return str_split($this->get(), $length);
    }

    /**
     * Remove keys from a string.
     * 
     * @param   mixed $keys
     * @return  $this
     */

    public function remove($keys)
    {
        return $this->set($this->replace($keys, '')->get());
    }

    /**
     * Find and replace string.
     * 
     * @param   mixed $find
     * @param   string $replacement
     * @return  $this
     */

    public function replace($find, string $replace)
    {
        return $this->set(str_replace($find, $replace, $this->get()));
    }

    /**
     * Shuffle the characters from the string.
     * 
     * @return $this
     */

    public function shuffle()
    {
        return $this->set(str_shuffle($this->get()));
    }

    /**
     * Reverse the string.
     * 
     * @return $this
     */

    public function reverse()
    {
        return $this->set(strrev($this->get()));
    }

    /**
     * Append line break at the end of the string.
     * 
     * @return $this
     */

    public function br()
    {
        return $this->append(PHP_EOL);
    }

    /**
     * Compare string values.
     * 
     * @param   mixed $keys
     * @return  bool
     */

    public function equal($keys)
    {
        if(is_array($keys))
        {
            foreach($keys as $key)
            {
                if((string)$key === $this->get())
                {
                    return true;
                }
            }

            return false;
        }
        else
        {
            return $this->string === (string)$keys;
        }
    }

    /**
     * Find the position of the first occurance of a substring in a string.
     * 
     * @param   string $delimeter
     * @return  int
     */

    public function pos(string $delimeter)
    {
        return strpos($this->get(), $delimeter);
    }

    /**
     * Execute substring function into string.
     * 
     * @param   int $start
     * @param   int $length
     * @return  string
     */

    public function substr(int $start, int $length)
    {
        return substr($this->get(), $start, $length);
    }

    /**
     * Check if string start with the following keys.
     * 
     * @param   mixed $keys
     * @return  bool
     */

    public function startWith($keys)
    {
        if(is_array($keys))
        {
            foreach($keys as $key)
            {
                if(substr($this->get(), 0, strlen($key)) === $key)
                {
                    return true;
                }
            }

            return false;
        }
        else
        {
            return substr($this->get(), 0, strlen($keys)) === $keys;
        }
    }

    /**
     * Check if string end with the following keys.
     * 
     * @param   mixed $keys
     * @return  bool
     */

    public function endWith($keys)
    {
        $length = $this->length();

        if(is_array($keys))
        {
            foreach($keys as $key)
            {
                if(substr($this->get(), $length - strlen($key), $length) === $key)
                {
                    return true;
                }
            }

            return false;
        }
        else
        {
            return substr($this->get(), $length - strlen($keys), $length) === $keys;
        }
    }

    /**
     * Move string pointer from start to end.
     * 
     * @param   int $start
     * @param   int $end
     * @return  $this
     */

    public function move(int $start, int $end = null)
    {
        $string = substr($this->get(), $start, $this->length());

        if(!is_null($end))
        {
            $string = substr($string, 0, strlen($string) - $end);
        }

        return $this->set($string);
    }

    /**
     * Keep on moving in the start of the string.
     * 
     * @param   string $delimeter
     * @return  $this
     */

    public function moveFromStart(string $delimeter)
    {
        while($this->startWith($delimeter))
        {
            $this->set($this->move(strlen($delimeter))->get());
        }

        return $this;
    }

    /**
     * Keep moving at the end of the string.
     * 
     * @param   string $delimeter
     * @return  $this
     */

    public function moveFromEnd(string $delimeter)
    {
        while($this->endWith($delimeter))
        {
            $this->set($this->move(0, strlen($delimeter))->get());
        }

        return $this;
    }

    /**
     * Keep on moving from both ends of the string.
     * 
     * @param   string $delimeter
     * @return  $this
     */

    public function moveFromBothEnds(string $delimeter)
    {
        $this->moveFromStart($delimeter);
        $this->moveFromEnd($delimeter);

        return $this;
    }

    /**
     * Return true if string contains a keyword.
     * 
     * @param   mixed $keys
     * @return  $this
     */

    public function has($keys)
    {
        if(is_array($keys))
        {
            foreach($keys as $key)
            {
                if($this->pos($key) !== false)
                {
                    return true;
                }
            }

            return false;
        }
        else
        {
            return $this->pos($keys) !== false;
        }
    }

    /**
     * Hash string using hashing algorithm from PHP.
     * 
     * @param   string $algo
     * @return  $this
     */

    public function hash(string $algo = 'md5')
    {
        if(in_array($algo, hash_algos()))
        {
            $this->set(hash($algo, $this->get()));
        }

        return $this;
    }

    /**
     * Split the string in to two segment.
     * 
     * @param   string $delimeter
     * @return  array
     */

    public function break(string $delimeter)
    {
        if($this->has($delimeter))
        {
            $position = $this->pos($delimeter);
            return [substr($this->get(), 0, $position), substr($this->get(), $position + strlen($delimeter), $this->length())];
        }
        else
        {
            return [$this->get()];
        }
    }

    /**
     * Trim the string.
     * 
     * @return $this
     */

    public function trim()
    {
        return $this->set(trim($this->get()));
    }

    /**
     * Count the words from the string.
     * 
     * @return  int
     */

    public function countWords()
    {
        $words = 0;
        foreach($this->trim()->explode(' ') as $word)
        {
            if($word !== '')
            {
                $words++;
            }
        }

        return $words;
    }

    /**
     * Count the number of lines from the string.
     * 
     * @return int
     */

    public function countLines()
    {
        return (int)substr_count($this->get(), "\n") + 1;
    }

    /**
     * Count the number of occurance of numbers from string.
     * 
     * @return int
     */

    public function countNumbers()
    {
        return (int)count(preg_grep('~^[0-9]$~', str_split($this->get())));
    }

    /**
     * Count all non-alphanumeric characters from string.
     * 
     * @return  int
     */

    public function countNonAlphaNumeric()
    {
        return $this->length() - strlen(preg_replace('/[^[:alnum:]]/', '', $this->get()));
    }

    /**
     * Count the number of occurance of letters.
     * 
     * @param   string $case
     * @return  int
     */

    public function countLetters(string $case = null)
    {
        $case = strtolower($case);
        $string = $this->get();

        if($case === 'uppercase')
        {
            return strlen(preg_replace('![^A-Z]+!', '', $string));
        }
        else if($case === 'lowercase')
        {
            return strlen(preg_replace('![^a-z]+!', '', $string));
        }
        else
        {
            return strlen(preg_replace('![^A-za-z]+!', '', $string));
        }
    }

    /**
     * Print the string from the screen.
     * 
     * @return  $this
     */

    public function print()
    {
        echo (string)$this->get();

        return $this;
    }

    /**
     * Return true if string is numeric.
     * 
     * @return  bool
     */

    public function isNumeric()
    {
        return is_numeric($this->get()) || is_int($this->get());
    }

}