<?php

namespace Voyager\Http\Validation;

class Param extends ValidateBase
{
    /**
     * Contains all registered parameters.
     * 
     * @var array
     */

    private static $params = [];

    /**
     * Create new instance of param class.
     * 
     * @param   string $name
     * @return  void
     */

    private function __construct(string $id)
    {
        $this->init();
        $this->set('name', $id);
    }

    /**
     * Set parameter to optional.
     * 
     * @param   bool $optional
     * @return  $this
     */

    public function optional(bool $optional)
    {
        return $this->set('optional', $optional);
    }

    /**
     * Set parameter as numeric value.
     * 
     * @return  $this
     */

    public function numeric()
    {
        return $this->set('numeric', true);
    }

    /**
     * Set parameter minimum length.
     * 
     * @param   int $min
     * @return  $this
     */

    public function minLength(int $min)
    {
        return $this->set('min_length', $min);
    }

    /**
     * Set parameter maximum length.
     * 
     * @param   int $max
     * @return  $this
     */

    public function maxLength(int $max)
    {
        return $this->set('max_length', $max);
    }

    /**
     * Set parameter minimum word.
     * 
     * @param   int $min
     * @return  $this
     */

    public function minWord(int $min)
    {
        return $this->set('min_word', $min);
    }

    /**
     * Set parameter maximum words.
     * 
     * @param   int $max
     * @return  $this
     */

    public function maxWord(int $max)
    {
        return $this->set('max_word', $max);
    }

    /**
     * Set parameter minimum value for numeric values.
     * 
     * @param   int $min
     * @return  $this
     */

    public function minValue(int $min)
    {
        return $this->set('min_value', $min);
    }

    /**
     * Set parameter maximum value for numeric values.
     * 
     * @param   int $max
     * @return  $this
     */

    public function maxValue(int $max)
    {
        return $this->set('max_value', $max);
    }

    /**
     * Set parameter minimum length.
     * 
     * @param   int $min
     * @return  $this
     */

    public function minLine(int $min)
    {
        return $this->set('min_line', $min);
    }

    /**
     * Set parameter maximum line.
     * 
     * @param   int $max
     * @return  $this
     */

    public function maxLine(int $max)
    {
        return $this->set('max_line', $max);
    }

    /**
     * Set parameter minimum letters.
     * 
     * @param   int $min
     * @return  $this
     */

    public function minLetters(int $min)
    {
        return $this->set('min_letters', $min);
    }

    /**
     * Set parameter maximum letters.
     * 
     * @param   int $max
     * @return  $this
     */

    public function maxLetters(int $max)
    {
        return $this->set('max_letters', $max);
    }

    /**
     * Set parameter minimum numbers.
     * 
     * @param   int $min
     * @return  $this
     */

    public function minNumbers(int $min)
    {
        return $this->set('min_numbers', $min);
    }

    /**
     * Set parameter maximum numbers.
     * 
     * @param   int $max
     * @return  $this
     */

    public function maxNumbers(int $max)
    {
        return $this->set('max_numbers', $max);
    }

    /**
     * Set parameter minimum lowercase letters.
     * 
     * @param   int $min
     * @return  $this
     */

    public function minLowercaseLetters(int $min)
    {
        return $this->set('min_lowercase', $min);
    }

    /**
     * Set parameter maximum lowercase letters.
     * 
     * @param   int $max
     * @return  $this
     */

    public function maxLowercaseLetters(int $max)
    {
        return $this->set('max_lowercase', $max);
    }

    /**
     * Set parameter maximum lowercase letters.
     * 
     * @param   int $min
     * @return  $this
     */

    public function minUppercaseLetters(int $min)
    {
        return $this->set('min_uppercase', $min);
    }

    /**
     * Set parameter maximum uppercase letters.
     * 
     * @param   int $max
     * @return  $this
     */

    public function maxUppercaseLetters(int $max)
    {
        return $this->set('max_uppercase', $max);
    }

    /**
     * Set parameter minimum non-alphanumeric characters.
     * 
     * @param   int $min
     * @return  $this
     */

    public function minNonAlphanumeric(int $min)
    {
        return $this->set('min_nonalphanumeric', $min);
    }

    /**
     * Set parameter maximum non-alphanumeric characters.
     * 
     * @param   int $max
     * @return  $this
     */

    public function maxNonAlphanumeric(int $max)
    {
        return $this->set('max_nonalphanumeric', $max);
    }

    /**
     * Set parameter start with.
     * 
     * @param   array $startwith
     * @return  $this
     */

    public function startWith(array $startwith)
    {
        return $this->set('start_with', $startwith);
    }

    /**
     * Set parameter end with.
     * 
     * @param   array $endwith
     * @return  $this
     */

    public function endWith(array $endwith)
    {
        return $this->set('end_with', $endwith);
    }

    /**
     * If value contains strings from array.
     * 
     * @param   array $contains
     * @return  $this
     */

    public function contains(array $contains)
    {
        return $this->set('contains', $contains);
    }

    /**
     * Value must not contain strings from array.
     * 
     * @param   array $contains
     */

    public function notContain(array $contains)
    {
        return $this->set('not_contain', $contains);
    }

    /**
     * Value must not contains values from the array.
     * 
     * @param   array $mustnot
     * @return  $this
     */

    public function mustNot(array $mustnot)
    {
        return $this->set('must_not', $mustnot);
    }

    /**
     * Value must be at least one of the values from array.
     * 
     * @param   array $expect
     * @return  $this
     */

    public function expect(array $expect)
    {
        return $this->set('expect', $expect);
    }

    /**
     * Value must follow the regex pattern provided.
     * 
     * @param   mixed $regex
     * @return  $this
     */

    public function regexPattern($regex)
    {
        return $this->set('regex_pattern', $regex);
    }

    /**
     * Create new instance of parameter object.
     * 
     * @param   string $id
     */

    public static function id(string $id)
    {
        $instance = new self($id);
        static::$params[$id] = $instance;

        return $instance;
    }

    /**
     * Return array of parameter data.
     * 
     * @return  array
     */

    public static function get()
    {
        return static::$params;
    }

}