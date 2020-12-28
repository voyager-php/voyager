<?php

namespace Voyager\Http\Validation;

use Voyager\Facade\Str;
use Voyager\Util\Data\Collection;

class Validate extends ValidateBase
{
    /**
     * Store validation configuration data.
     * 
     * @var array
     */

    private static $validations;

    /**
     * Validation response code.
     * 
     * @var int
     */

    private $code = -1;

    /**
     * Store override data.
     * 
     * @var array
     */

    private $override;

    /**
     * Store validation type.
     * 
     * @var string
     */

    private $type;

    /**
     * Create new validate instance.
     * 
     * @param   string $type
     * @param   array $override
     * @return  void
     */

    public function __construct(string $type, ?string $value, bool $optional, array $override = null)
    {
        $this->type = $type;
        $this->override = $override;
        
        if(is_null(static::$validations))
        {
            static::$validations = Config::get();
        }

        $this->test($value, $optional);
    }

    /**
     * Evaluate value and determine validation code.
     * 
     * @param   string $value
     * @param   bool $optional
     * @return  $this
     */

    private function test(?string $value, bool $optional = false)
    {
        $code = -1;
        $value = Str::make($value ?? '');
        $data = new Collection(static::$validations[$this->type]);
        $length = $value->length();

        if(!is_null($this->override))
        {
            $data->override($this->override);
        }

        if(!$value->empty())
        {
            if($data->numeric)
            {
                if($value->isNumeric())
                {
                    $val = $value->get();

                    if(is_null($data->min_value) || (!is_null($data->min_value) && $data->min_value <= $val))
                    {
                        if(is_null($data->max_value) || (!is_null($data->max_value) && $data->max_value >= $val))
                        {
                            if(sizeof($data->must_not) === 0 || (sizeof($data->must_not) !== 0 && !in_array($val, $data->must_not)))
                            {
                                if(sizeof($data->expect) === 0 || (sizeof($data->expect) !== 0 && in_array($val, $data->expect)))
                                {
                                    $code = -1;
                                }
                                else
                                {
                                    $code = 5;
                                }
                            }
                            else
                            {
                                $code = 4;
                            }
                        }
                        else
                        {
                            $code = 3;
                        }
                    }
                    else
                    {
                        $code = 2;
                    }
                }
                else
                {
                    $code = 1;
                }
            }
            else
            {
                if($data->min_length <= $length)
                {
                    if(is_null($data->max_length) || (!is_null($data->max_length) && $data->max_length >= $length))
                    {
                        if(is_null($data->min_word) || (!is_null($data->min_word) && Str::countWords($value->get()) >= $data->min_word))
                        {
                            if(is_null($data->max_word) || (!is_null($data->max_word) && Str::countWords($value->get()) <= $data->max_word))
                            {
                                if(is_null($data->min_line) || (!is_null($data->min_line) && Str::countLines($value->get()) >= $data->min_line))
                                {
                                    if(is_null($data->max_line) || (!is_null($data->max_line) && Str::countLines($value->get()) <= $data->max_line))
                                    {
                                        if(is_null($data->min_letters) || (!is_null($data->min_letters) && Str::countLetters($value->get()) >= $data->min_letters))
                                        {
                                            if(is_null($data->max_letters) || (!is_null($data->max_letters) && Str::countLetters($value->get()) <= $data->max_letters))
                                            {
                                                if(is_null($data->min_lowercase) || (!is_null($data->min_lowercase) && Str::countLetters($value->get(), 'lowercase') >= $data->min_lowercase))
                                                {
                                                    if(is_null($data->max_lowercase) || (!is_null($data->max_lowercase) && Str::countLetters($value->get(), 'lowercase') <= $data->max_lowercase))
                                                    {
                                                        if(is_null($data->min_uppercase) || (!is_null($data->min_uppercase) && Str::countLetters($value->get(), 'uppercase') >= $data->min_uppercase))
                                                        {
                                                            if(is_null($data->max_uppercase) || (!is_null($data->max_uppercase) && Str::countLetters($value, 'uppercase') <= $data->max_uppercase))
                                                            {
                                                                if(is_null($data->min_numbers) || (!is_null($data->min_numbers) && Str::countNumbers($value) >= $data->min_numbers))
                                                                {
                                                                    if(is_null($data->max_numbers) || (!is_null($data->max_numbers) && Str::countNumbers($value) <= $data->max_numbers))
                                                                    {
                                                                        if(is_null($data->min_nonalphanumeric) || (!is_null($data->min_nonalphanumeric) && Str::countNonAlphaNumeric($value) >= $data->min_nonalphanumeric))
                                                                        {
                                                                            if(is_null($data->max_nonalphanumeric) || (!is_null($data->max_nonalphanumeric) && Str::countNonAlphaNumeric($value) <= $data->max_nonalphanumeric))
                                                                            {
                                                                                if(sizeof($data->start_with) === 0 || (sizeof($data->start_with) !== 0 && Str::startWith($value->get(), $data->start_with)))
                                                                                {
                                                                                    if(sizeof($data->end_with) === 0 || (sizeof($data->end_with) !== 0 && Str::endWith($value->get(), $data->end_with)))
                                                                                    {
                                                                                        if(sizeof($data->contains) === 0  || (sizeof($data->contains) !== 0 && Str::has($value->get(), $data->contains)))
                                                                                        {
                                                                                            if(sizeof($data->not_contain) === 0 || (sizeof($data->not_contain) !== 0 && !Str::has($value->get(), $data->not_contain)))
                                                                                            {
                                                                                                if(sizeof($data->must_not) === 0 || (sizeof($data->must_not) !== 0 && !in_array($value->get(), $data->must_not)))
                                                                                                {
                                                                                                    if(sizeof($data->expect) === 0 || (sizeof($data->expect) !== 0 && in_array($value->get(), $data->expect)))
                                                                                                    {
                                                                                                        if(is_null($data->regex_pattern) || (!is_null($data->regex_pattern) && preg_match($data->regex_pattern, $value->get())))
                                                                                                        {
                                                                                                            $code = -1;
                                                                                                        }
                                                                                                        else
                                                                                                        {
                                                                                                            $code = 23; 
                                                                                                        }
                                                                                                    }
                                                                                                    else
                                                                                                    {
                                                                                                        $code = 22;
                                                                                                    }
                                                                                                }
                                                                                                else
                                                                                                {
                                                                                                    $code = 21; 
                                                                                                }
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                $code = 20;
                                                                                            }
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            $code = 19;
                                                                                        }
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        $code = 18;   
                                                                                    }
                                                                                }
                                                                                else
                                                                                {
                                                                                    $code = 17;
                                                                                }
                                                                            }
                                                                            else
                                                                            {
                                                                                $code = 16;
                                                                            }
                                                                        }
                                                                        else
                                                                        {
                                                                            $code = 15;
                                                                        }
                                                                    }
                                                                    else
                                                                    {
                                                                        $code = 14;
                                                                    }
                                                                }
                                                                else
                                                                {
                                                                    $code = 13;
                                                                }
                                                            }
                                                            else
                                                            {
                                                                $code = 12;
                                                            }
                                                        }
                                                        else
                                                        {
                                                            $code = 11;
                                                        }
                                                    }
                                                    else
                                                    {
                                                        $code = 10;
                                                    }
                                                }
                                                else
                                                {
                                                    $code = 9;
                                                }
                                            }
                                            else
                                            {
                                                $code = 8;
                                            }
                                        }
                                        else
                                        {
                                            $code = 7;
                                        }
                                    }
                                    else
                                    {
                                        $code = 6;
                                    }
                                }
                                else
                                {
                                    $code = 5;
                                }
                            }
                            else
                            {
                                $code = 4;
                            }
                        }
                        else
                        {
                            $code = 3;
                        }
                    }
                    else
                    {
                        $code = 2;
                    }
                }
                else
                {
                    $code = 1;
                }
            }

            if($code !== -1 && $optional)
            {
                $code = -1;
            }
        }
        else
        {
            if(!$optional)
            {
                $code = 0;
            }
        }

        $this->code = $code;
    }

    /**
     * Return error code.
     * 
     * @return  int
     */

    public function getErrorCode()
    {
        return $this->code;
    }

    /**
     * Return true if input value is valid.
     * 
     * @return  bool
     */

    public function isValid()
    {
        return $this->code === -1;
    }

}