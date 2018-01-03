<?php

namespace Libs\Mpdf;

use AppBundle\Utility\StringUtils;

define('_MPDF_SYSTEM_TTFONTS_CONFIG', __DIR__ . '/Fonts/config.php');
define('_MPDF_TTFONTPATH', __DIR__ . '/Fonts/');

class AppPDF extends \mPDF
{
    function docPageNum($num = 0, $extras = false) {
        return StringUtils::translateNumbers(parent::docPageNum($num, $extras));
    }
}