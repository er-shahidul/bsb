<?php

namespace AppBundle\Twig;

use AppBundle\Utility\StringUtils;

class AppTranslationExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_Filter('bn_number', array($this, 'bnNumber')),
            new \Twig_Filter('bn_number_format', array($this, 'bnNumberFormat')),
            new \Twig_Filter('en_number_format', array($this, 'enNumberFormat')),
            new \Twig_Filter('head_star', array($this, 'headStar')),
        );
    }

    public function bnNumber($number)
    {
        return StringUtils::translateNumbers($number);
    }

    public function bnNumberFormat($number)
    {
        return $this->bnNumber($this->enNumberFormat($number));
    }

    public function enNumberFormat($number)
    {
        if (empty($number) && $number !== 0 ) { return ''; }

        return $this->custom_number_format((float)$number);
    }

    function custom_number_format($number)
    {
        $fraction = explode('.', $number);
        $n = $fraction[0];
        $n = strrev($n);
        $d = 3;
        if (strlen($n) > $d) {
            $n = substr($n, 0, $d) . ',' . implode(',', str_split(substr($n, $d), 2));
        }
        $fraction[0] = strrev($n);

        return implode('.', $fraction);
    }

    public function headStar($number)
    {
        if (empty($number) || !is_numeric($number)  ) { return ''; }

        return str_repeat('*', $number);
    }
}