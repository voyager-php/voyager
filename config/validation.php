<?php

namespace Voyager\Http\Validation {

    Param::id('text');
    
    Param::id('number')
        ->minValue(0)
        ->numeric();

    Param::id('boolean')
        ->minValue(0)
        ->maxValue(1)
        ->numeric();

    Param::id('email')
        ->maxLength(320)
        ->endWith(['@gmail.com', '@yahoo.com'])
        ->regexPattern('/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/');

    Param::id('username')
        ->minLength(6)
        ->maxLength(24)
        ->maxWord(1)
        ->regexPattern('/^[a-z0-9][a-z0-9_]{2,28}[a-z0-9]$/');

    Param::id('password')
        ->minLength(8)
        ->maxLength(128)
        ->maxWord(1)
        ->maxNonAlphaNumeric(0)
        ->notContain(['password']);

    Param::id('name')
        ->regexPattern('/^[a-zA-Z]+(?:\s[a-zA-Z]+)+$/');

    Param::id('month')
        ->minValue(1)
        ->maxValue(12)
        ->numeric();

    Param::id('day')
        ->minValue(1)
        ->maxValue(31)
        ->numeric();

    Param::id('year')
        ->minValue((int)date('Y') - 100)
        ->maxValue((int)date('Y'))
        ->numeric();

}