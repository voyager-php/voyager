<?php

namespace Voyager\Database;

use Voyager\Facade\Str;
use Voyager\Util\Str as Builder;

abstract class DBWhereBuilder extends DBOperations
{
    /**
     * Return where statements.
     * 
     * @return  array
     */

    protected function getWhere()
    {
        return $this->where;
    }

    /**
     * Return generated where statement.
     * 
     * @return  string
     */

    protected function generateWhere()
    {
        if(!$this->where->empty())
        {
            $sql = new Builder();
            $startwith = false;
            
            foreach($this->where->get() as $where)
            {
                if(Str::startWith($where, 'AND') && !$startwith)
                {
                    $startwith = true;
                    $sql->append(Str::move($where, 4) . ' ');
                }
                else if(Str::startWith($where, 'OR') && !$startwith)
                {
                    $startwith = true;
                    $sql->append(Str::move($where, 3) . ' ');
                }
                else
                {
                    $sql->append($where . ' ');
                }
            }

            return $sql->get();
        }
    }

    /**
     * Generate where sql statement using array.
     * 
     * @param   string $operand
     * @param   string $key
     * @param   string $glue
     * @param   mixed $value
     * @return  string
     */

    private function generateFromArray(string $operand, string $key, string $glue, $value)
    {
        if(is_array($value))
        {
            if(!empty($value))
            {
                $sql = new Builder($glue);
                $sql->append(' (');

                foreach($value as $item)
                {
                    $sql->append($key . " ")
                        ->append($operand)
                        ->append(" ")
                        ->append(DB::escape($item))
                        ->append(" OR ");
                }

                if($sql->endWith(' OR '))
                {
                    $sql->move(0, 4);
                }

                $sql->append(')');
            }
            else
            {
                return '';
            }
        }
        else
        {
            $sql = new Builder($glue);
            $sql->append(" (" . $key . " ")
                ->append($operand)
                ->append(" ")
                ->append(DB::escape($value))
                ->append(")");
        }

        return $sql->get();
    }

    /**
     * Add raw where statement.
     * 
     * @param   string $where
     */

    public function raw(string $where)
    {
        $this->where->push($where);

        return $this;
    }

    /**
     * Generate equality where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @param   string $glue
     * @return  $this
     */

    private function makeEqual(string $key, $value, string $glue = 'AND')
    {
        return $this->raw($this->generateFromArray('=', $key, $glue, $value));
    }

    /**
     * Generate equality where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function equal(string $key, $value)
    {
        return $this->makeEqual($key, $value);
    }

    /**
     * Generate equality where statement.
     * 
     * @param   string $key
     * @param   mixed $value 
     * @return  $this
     */

    public function orEqual(string $key, $value)
    {
        return $this->makeEqual($key, $value, 'OR');
    }

    /**
     * Generate statement if field is null.
     * 
     * @param   string $key
     * @return  $this
     */

    public function isNull(string $key)
    {
        return $this->raw('AND ' . $key . ' IS NULL');
    }

    /**
     * Generate statement if field is null.
     * 
     * @param   string $key
     * @return  $this
     */

    public function orIsNull(string $key)
    {
        return $this->raw('OR ' . $key . ' IS NULL');
    }

    /**
     * Generate statement if field is not null.
     * 
     * @param   string $key
     * @return  $this
     */

    public function isNotNull(string $key)
    {
        return $this->raw('AND ' . $key . ' IS NOT NULL');
    }

    /**
     * Generate statement if field is not null.
     * 
     * @param   string $key
     * @return  $this
     */

    public function orIsNotNull(string $key)
    {
        return $this->raw('OR ' . $key . ' IS NOT NULL');
    }

    /**
     * Generate statement if field is empty.
     * 
     * @param   string $key
     * @return  $this
     */

    public function isEmpty(string $key)
    {
        return $this->equal($key, '');
    }

    /**
     * Generate statement if field is empty.
     * 
     * @param   string $key
     * @return  $this
     */

    public function orIsEmpty(string $key)
    {
        return $this->equal($key, '');
    }

    /**
     * Generate not equal where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @param   string $glue
     * @return  $this
     */

    private function makeNotEqual(string $key, $value, string $glue = 'AND')
    {
        return $this->raw($this->generateFromArray('!=', $key, $glue, $value));
    }

    /**
     * Generate not equal where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function notEqual(string $key, $value)
    {
        return $this->makeNotEqual($key, $value);
    }

    /**
     * Generate not equal where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function orNotEqual(string $key, $value)
    {
        return $this->makeNotEqual($key, $value, 'OR');
    }

    /**
     * Generate not empty where statement.
     * 
     * @param   string $key
     * @return  $this
     */

    public function isNotEmpty(string $key)
    {
        return $this->notEqual($key, '');
    }

    /**
     * Generate not empty where statement.
     * 
     * @param   string $key
     * @return  $this
     */

    public function orIsNotEmpty(string $key)
    {
        return $this->orNotEqual($key, '');
    }

    /**
     * Generate less than where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @param   string $glue
     * @return  $this
     */

    private function makeLessThan(string $key, $value, string $glue = 'AND')
    {
        return $this->raw($this->generateFromArray('<', $key, $glue, $value));
    }

    /**
     * Generate less than where statement.
     * 
     * @param   string $key
     * @param   string $value
     * @return  $this
     */

    public function lessThan(string $key, $value)
    {
        return $this->makeLessThan($key, $value);
    }

    /**
     * Generate less than where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function orLessThan(string $key, $value)
    {
        return $this->makeLessThan($key, $value, 'OR');
    }

    /**
     * Generate less than where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @param   string $glue
     * @return  $this
     */

    private function makeLessThanOrEqual(string $key, $value, string $glue = 'AND')
    {
        return $this->raw($this->generateFromArray('<=', $key, $glue, $value));
    }

    /**
     * Generate less than equal where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function lessThanOrEqual(string $key, $value)
    {
        return $this->makeLessThanOrEqual($key, $value);
    }

    /**
     * Generate less than where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function orLessThanEqual(string $key, $value)
    {
        return $this->makeLessThanOrEqual($key, $value, 'OR');
    }

    /**
     * Generate more than where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @param   string $glue
     * @return  $this
     */

    private function makeMoreThan(string $key, $value, string $glue = 'AND')
    {
        return $this->raw($this->generateFromArray('>', $key, $glue, $value));
    }

    /**
     * Generate more than where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function moreThan(string $key, $value)
    {
        return $this->makeMoreThan($key, $value);
    }

    /**
     * Generate more than where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function orMoreThan(string $key, $value)
    {
        return $this->makeMoreThan($key, $value, 'OR');
    }
    
    /**
     * Generate more than where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @param   string $glue
     * @return  $this
     */

    private function makeMoreThanEqual(string $key, $value, string $glue = 'AND')
    {
        return $this->raw($this->generateFromArray('>=', $key, $glue, $value));
    }

    /**
     * Generate more than where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function moreThanEqual(string $key, $value)
    {
        return $this->makeMoreThanEqual($key, $value);
    }

    /**
     * Generate more than where statement.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function orMoreThanEqual(string $key, $value)
    {
        return $this->makeMoreThanEqual($key, $value, 'OR');
    }

    /**
     * Find value that start with.
     * 
     * @param   string $key
     * @param   mixed $value
     * @param   string $glue
     * @return  $this
     */

    private function makeStartWith(string $key, $value, string $glue = 'AND')
    {
        if(is_array($value))
        {
            $sql = new Builder($glue);
            $sql->append(' (');

            foreach($value as $item)
            {
                $sql->append($key)
                    ->append(' LIKE ')
                    ->append(DB::escape($item . '%'))
                    ->append(' OR ');
            }

            if($sql->endWith(' OR '))
            {
                $sql->move(0, 4);
            }

            $sql->append(')');
        }
        else
        {
            $sql = new Builder($glue);
            $sql->append(' ' . $key)
                ->append('LIKE ')
                ->append(DB::escape($value . '%'));
        }

        return $this->raw($sql->get());
    }

    /**
     * Find value that start with.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function startWith(string $key, $value)
    {
        return $this->makeStartWith($key, $value);
    }

    /**
     * Find value that start with.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function orStartWith(string $key, $value)
    {
        return $this->makeStartWith($key, $value, 'OR');
    }

    /**
     * Find value that ends with.
     * 
     * @param   string $key
     * @param   mixed $value
     * @param   string $glue
     */

    public function makeEndWith(string $key, $value, string $glue = 'AND')
    {
        if(is_array($value))
        {
            $sql = new Builder($glue);
            $sql->append(' (');

            foreach($value as $item)
            {
                $sql->append($key)
                    ->append('LIKE ')
                    ->append(DB::escape('%' . $item))
                    ->append(' OR ');
            }

            if($sql->endWith(' OR '))
            {
                $sql->move(0, 4);
            }

            $sql->append(')');
        }
        else
        {
            $sql = new Builder($glue);
            $sql->append(' ')
                ->append($key)
                ->append(' LIKE ')
                ->append(DB::escape('%' . $value));
        }

        return $this->raw($sql->get());
    }

    /**
     * Find value that ends with.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function endWith(string $key, $value)
    {
        return $this->makeEndWith($key, $value);
    }

    /**
     * Find value that ends with.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function orEndWith(string $key, $value)
    {
        return $this->makeEndWith($key, $value, 'OR');
    }

    /**
     * Find value that looks a like.
     * 
     * @param   string $key
     * @param   mixed $value
     * @param   string $glue
     * @return  $this
     */

    public function makeLike(string $key, $value, string $glue = 'AND')
    {
        $sql = new Builder($glue);

        if(is_array($value))
        {
            $sql->append(' (');

            foreach($value as $item)
            {
                $sql->append($key)
                    ->append(' LIKE ')
                    ->append(DB::escape('%' . $item . '%'))
                    ->append(' OR ');
            }

            if($sql->endWith(' OR '))
            {
                $sql->move(0, 4);
            }

            $sql->append(')');
        }
        else
        {
            $sql->append($key)
                ->append(' LIKE ')
                ->append(DB::escape('%' . $value . '%'));
        }

        return $this->raw($sql->get());
    }

    /**
     * Find value that looks a like.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function like(string $key, $value)
    {
        return $this->makeLike($key, $value);
    }

    /**
     * Find value that looks a like.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function orLike(string $key, $value)
    {
        return $this->makeLike($key, $value, 'OR');
    }

    /**
     * Generate not like statements.
     * 
     * @param   string $key
     * @param   mixed $value
     * @param   string $glue
     * @return  $this
     */

    private function makeNotLike(string $key, $value, string $glue = 'AND')
    {
        $sql = new Builder($glue);

        if(is_array($value))
        {
            $sql->append(' (');

            foreach($value as $item)
            {
                $sql->append($key)
                    ->append(' NOT LIKE ')
                    ->append(DB::escape('%' . $item . '%'))
                    ->append(' OR ');
            }

            if($sql->endWith(' OR '))
            {
                $sql->move(0, 4);
            }

            $sql->append(')');
        }
        else
        {
            $sql->append($key)
                ->append(' NOT LIKE ')
                ->append(DB::escape('%' . $value . '%'));
        }

        return $this->raw($sql->get());
    }

    /**
     * Generate not like where statements.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function notLike(string $key, $value)
    {
        return $this->makeNotLike($key, $value);
    }

    /**
     * Generate not like where statements.
     * 
     * @param   string $key
     * @param   mixed $value
     * @return  $this
     */

    public function orNotLike(string $key, $value)
    {
        return $this->makeNotLike($key, $value, 'OR');
    }

    /**
     * Check if updated column is not null.
     * 
     * @return $this
     */

    public function isEdited()
    {
        return $this->isNotNull('updated');
    }

    /**
     * Check if updated column is not null.
     * 
     * @return  $this
     */

    public function orIsEdited()
    {
        return $this->orIsNotNull('updated');
    }

    /**
     * Check if updated column is null.
     * 
     * @return $this
     */

    public function isNotEdited()
    {
        return $this->isNull('updated');
    }

    /**
     * Check if updated column is null.
     * 
     * @return  $this
     */

    public function orIsNotEdited()
    {
        return $this->orIsNull('updated');
    }

    /**
     * Check if deleted column is not null.
     * 
     * @return $this
     */

    public function isDeleted()
    {
        return $this->isNotNull('deleted');
    }

    /**
     * Check if deleted column is not null.
     * 
     * @return  $this
     */

    public function orIsDeleted()
    {
        return $this->orIsNotNull('deleted');
    }

    /**
     * Check if deleted column is null.
     * 
     * @return $this
     */

    public function isNotDeleted()
    {
        return $this->isNull('deleted');
    }

    /**
     * Check if deleted column is null.
     * 
     * @return $this
     */

    public function orIsNotDeleted()
    {
        return $this->orIsNull('deleted');
    }

    /**
     * Check if column's value is equal to zero.
     * 
     * @param   string $key
     * @return  $this
     */

    public function isZero(string $key)
    {
        return $this->equal($key, '0');
    }

    /**
     * Check if column's value is equal to zero.
     * 
     * @param   string $key
     * @return  $this
     */

    public function orIsZero(string $key)
    {
        return $this->orEqual($key, '0');
    }

    /**
     * Check if column's value is not equal to zero.
     * 
     * @param   string $key
     * @return  $this
     */

    public function isNotZero(string $key)
    {
        return $this->notEqual($key, '0');
    }

    /**
     * Check if column's value is not equal to zero.
     * 
     * @param   string $key
     * @return  $this
     */

    public function orIsNotZero(string $key)
    {
        return $this->orNotEqual($key, '0');
    }

}