<?php

namespace Libs\Mpdf;

class MpdfFactory
{
    /**
     * @return AppPDF
     */
    public static function create()
    {
        $mpdf = new AppPDF();
        $mpdf->defaultfooterfontstyle = false;
        $mpdf->defaultfooterline = false;
        $mpdf->defaultheaderfontstyle = false;
        $mpdf->defaultheaderline = false;

        $mpdf->setMBencoding("UTF-8");

        return $mpdf;
    }
}
