<?php

namespace AppBundle\Twig;

use AppBundle\Utility\DateUtil;
use EasyBanglaDate\Types\BnDateTime;

class AppDateExtension extends \Twig_Extension
{
    const DEFAULT_MORNING_VALUE = 6;

    public function getFilters()
    {
        return array(
            new \Twig_Filter('bn_date', array($this, 'bnDate')),
            new \Twig_Filter('date_bn_ex', array($this, 'dateBnEx')),
            new \Twig_Filter('date_bn', array($this, 'dateBn')),
            new \Twig_Filter('age', array($this, 'age')),
            new \Twig_Filter('time_span', array($this, 'timeSpan')),
        );
    }

    public function bnDate($date, $format, $morning = self::DEFAULT_MORNING_VALUE)
    {
        $dateTime = BnDateTime::create($date);
        $dateTime->setMorning($morning);
        return $dateTime->format($format);
    }

    public function dateBnEx($date, $format, $morning = self::DEFAULT_MORNING_VALUE)
    {
        $dateString = $this->dateBn($date, $format, $morning);

        if(strpos($format, 'M') === FALSE) {
            return $dateString;
        }

        return str_replace(
            array('জানু','ফেব্রু','সেপ্টে','অক্টো','নভে','ডিসে'),
            array('জানু:','ফেব্রু:','সেপ্টে:','অক্টো:','নভে:','ডিসে:'),
            $dateString
        );
    }

    public function dateBn($date, $format = 'jS F, Y')
    {
        return DateUtil::createEnDateTime($date)->format($format);
    }

    public function age(\DateTime $dob)
    {
        return DateUtil::age($dob);
    }

    public function timeSpan(\DateTime $base = NULL, \Datetime $to = NULL)
    {
        if($base === null || $to === NULL) {
            return NULL;
        }

        return DateUtil::age($base, 'full', $to);
    }
}