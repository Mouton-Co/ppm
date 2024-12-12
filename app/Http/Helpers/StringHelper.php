<?php

namespace App\Http\Helpers;

class StringHelper
{
    /**
     * Strip whitespace and linebreaks from a string.
     *
     * @param  string  $string
     */
    public static function stripWhitespaceAndLinebreaks($string): string
    {
        $pattern = '/\s*/m';
        $replace = '';

        return preg_replace($pattern, $replace, $string);
    }
}
