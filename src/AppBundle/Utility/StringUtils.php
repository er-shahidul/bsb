<?php

namespace AppBundle\Utility;

class StringUtils
{
    protected static $enDigit = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

    protected static $bnDigit = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');

    public static function translateNumbers($number)
    {
        $number .= "";

        if (empty($number) && $number !== '0') {
            return $number;
        }

        return str_replace(self::$enDigit, self::$bnDigit, $number);
    }
}