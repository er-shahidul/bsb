<?php

namespace AppBundle\Utility;

use EasyBanglaDate\Types\BnDateTime;
use EasyBanglaDate\Types\DateTime;

class DateUtil
{
    public static function getFinancialYear(\DateTime $date)
    {
        $year = (int)$date->format('Y');

        return $date->format('m') < 7 ? $year - 1 : $year;
    }

    public static function getCurrentFinancialYear()
    {
        return self::getFinancialYear(new \DateTime('now'));
    }

    public static function getNextFinancialYear()
    {
        return self::getCurrentFinancialYear() + 1;
    }


    /**
     * @param \DateTime $date
     *
     * @return \DateTime
     */
    public static function roundDate(\DateTime $date)
    {
        return $date->modify('23:59:59');
    }

    /**
     * @param \DateTime $date
     *
     * @return \DateTime
     */
    public static function dateRoundDown(\DateTime $date)
    {
        return $date->modify('00:00:00');
    }

    public static function getFYQuarterDateRange($financialYear)
    {
        $year = $financialYear instanceof \DateTime ? DateUtil::getFinancialYear($financialYear) : $financialYear;

        return [
            'first'  => [
                'start' => new \DateTime($year . '-07-01'),
                'end'   => DateUtil::roundDate(new \DateTime($year . '-09-30')),
            ],
            'second' => [
                'start' => new \DateTime($year . '-10-01'),
                'end'   => DateUtil::roundDate(new \DateTime($year . '-12-31')),
            ],
            'third'  => [
                'start' => new \DateTime($year + 1 . '-01-01'),
                'end'   => DateUtil::roundDate(new \DateTime($year . '-03-31')),
            ],
            'forth'  => [
                'start' => new \DateTime($year + 1 . '-04-01'),
                'end'   => DateUtil::roundDate(new \DateTime($year . '-06-30')),
            ],
        ];
    }

    public static function getFYDateRange($year)
    {
        return [
            'start' => new \DateTime($year . '-07-01'),
            'end'   => new \DateTime($year + 1 . '-06-30')
        ];
    }

    public static function createEnDateTime($time) {
        $dateTime = null;

        if (is_string($time)) {
            return new DateTime($time);
        } elseif ($time instanceof BnDateTime) {
            return $time->getDateTime();
        } elseif ($time instanceof \DateTime) {
            $dateTime = new DateTime($time);
            $dateTime->setTimestamp($time->getTimestamp());
            $dateTime->setTimezone($time->getTimezone());
        } elseif (is_int($time)) {
            $dateTime = new DateTime($time);
            $dateTime->setTimestamp($time);
        }

        return $dateTime;
    }

    public static function age(\DateTime $born, $format = 'full', \DateTime $now = NULL)
    {
        //set current date
        $now = $now === NULL ? new \DateTime : $now;

        //get differ between born date and current date
        $diff = $now->diff($born);

        $total_days = $diff->days;
        $total_months = ($diff->y * 12) + $diff->m;
        $total_years = $diff->y;
        //setup of localization if you want to use another language, PHP will translate it
        setlocale(LC_ALL, 'en_EN');

        //preparing format as on requested by second parameter
        switch ($format) {
            case 'full':
                $parts = [];
                if ($diff->y) {
                    $parts[] = $diff->y . ngettext(" year", " years", $diff->y);
                }

                if ($diff->m) {
                    $parts[] = $diff->m . ngettext(" month", " months", $diff->m);
                }

                if ($diff->d) {
                    $parts[] = $diff->d . ngettext(" day", " days", $diff->d);
                }

                $age = count($parts) < 3 ? implode(' and ', $parts) : $parts[0] . ', ' . $parts[1] . ' and ' . $parts[2];

                break;
            case 'M':
                $age = $total_months . ' ' . ngettext(" month", " months", $total_months);
                break;
            case 'D':
                $age = $total_days . ' ' . ngettext(" day", " days", $total_days);
                break;
            case 'Y':
                $age = $total_years . ' ' . ngettext(" year", " years", $total_years);
                break;
            case 'm':
                $age = $total_months;
                break;
            case 'd':
                $age = $total_days;
                break;
            case 'y':
                $age = $total_years;
                break;
            default:
                $age = str_replace(array('%y', '%m', '%d'),
                    array($diff->y, $diff->m, $diff->d),
                    str_replace(array('%Y', '%M', '%D'),
                        array($diff->y . ngettext(" year", " years", $diff->y),
                            $diff->m . ngettext(" month", " months", $diff->m),
                            $diff->d . ngettext(" day", " days", $diff->d)
                        ),
                        $format));
                break;
        }

        return $age;
    }

    public static function getMonthDateRange($year, $month)
    {
        $startDate = new \DateTime("{$year}-{$month}-01");
        return [
            'start' => $startDate,
            'end' => new \DateTime("{$year}-{$month}-".$startDate->format('t') . ' 23:59:59')
        ];
    }
}
