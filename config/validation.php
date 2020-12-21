<?php

namespace Voyager\Http\Validation {

    /**
     * TEXT
     * ========================================
     * Accept any kind of string values.
     */

    Param::id('text');

    /**
     * NUMBER
     * ========================================
     * Accept positive integer values.
     */
    
    Param::id('number')
        ->minValue(0)
        ->numeric();

    /**
     * BOOLEAN
     * ========================================
     * Accept only 1 or 0 numeric values.
     */

    Param::id('boolean')
        ->minValue(0)
        ->maxValue(1)
        ->numeric();

    /**
     * EMAIL
     * ========================================
     * Accept only valid email format.
     */

    Param::id('email')
        ->maxLength(320)
        // ->endWith(['@gmail.com', '@yahoo.com'])
        ->regexPattern('/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/');

    /**
     * USERNAME
     * ========================================
     * Accept username that accepts only letters
     * and numbers.
     */

    Param::id('username')
        ->minLength(6)
        ->maxLength(24)
        ->maxWord(1)
        ->regexPattern('/^[a-z0-9][a-z0-9_]{2,28}[a-z0-9]$/');

    /**
     * PASSWORD
     * ========================================
     * Accept password string with no special
     * characters. 
     */

    Param::id('password')
        ->minLength(8)
        ->maxLength(128)
        ->maxWord(1)
        ->maxNonAlphaNumeric(0)
        ->notContain(['password']);

    /**
     * NAME
     * ========================================
     * Accept string with universal name format.
     */

    Param::id('name')
        ->regexPattern('/^[a-zA-Z]+(?:\s[a-zA-Z]+)+$/');

    /**
     * MONTH
     * ========================================
     * Accept month numerical value.
     */

    Param::id('month')
        ->minValue(1)
        ->maxValue(12)
        ->numeric();

    /**
     * DAY
     * ========================================
     * Accept day numerical value.
     */

    Param::id('day')
        ->minValue(1)
        ->maxValue(31)
        ->numeric();

    /**
     * YEAR
     * ========================================
     * Accept year numerical value.
     */

    Param::id('year')
        ->minValue((int)date('Y') - 100)
        ->maxValue((int)date('Y'))
        ->numeric();

    /**
     * ZIP CODE
     * ========================================
     * Accept valid zip code values.
     */

    Param::id('zipcode')
        ->minValue(1000)
        ->maxValue(9999)
        ->numeric();

}