<?php

namespace AppBundle\Twig;

use AppBundle\Utility\FileUtil;

class FileTypeExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_Function('type_name', [$this, 'getTypeNameFromMime'])
        ];
    }

    function getTypeNameFromMime($mime)
    {
        return FileUtil::getTypeFromMime($mime);
    }
}
