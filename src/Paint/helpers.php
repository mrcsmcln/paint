<?php

use Doctrine\Common\Inflector\Inflector;

if (! function_exists('array_wrap')) {
    /**
     * If the given value is not an array, wrap it in one.
     *
     * @param  mixed  $value
     * @return array
     */
    function array_wrap($value)
    {
        return ! is_array($value) ? [$value] : $value;
    }
}

if (! function_exists('str_plural')) {
    /**
     * Get the plural form of an English word.
     *
     * @param  string  $value
     * @param  int     $count
     * @return string
     */
    function str_plural($value, $count = 2)
    {
        if ((int) $count === 1) {
            return $value;
        }

        return Inflector::pluralize($value);
    }
}

if (! function_exists('str_singular')) {
    /**
     * Get the plural form of an English word.
     *
     * @param  string  $value
     * @param  int     $count
     * @return string
     */
    function str_singular($value)
    {
        return Inflector::singularize($value);
    }
}

if (! function_exists('title_case')) {
    /**
     * Convert a value to title case.
     *
     * @param  string  $value
     * @return string
     */
    function title_case($value)
    {
        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
    }
}
