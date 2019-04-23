<?php

namespace KeenIO\Client\Operations\Parameters;

/**
 * Type Parameter Values
 * @package KeenIO\Client\Operations
 */
class ValueType
{
    const TYPE_STRING = 'string';
    const TYPE_NUMBER = 'number';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_ARRAY = array();
    const SET_STRING_ARRAY = array('string', 'array');
}