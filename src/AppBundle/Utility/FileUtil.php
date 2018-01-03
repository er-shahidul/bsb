<?php

namespace AppBundle\Utility;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File as FileObject;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

class FileUtil
{
    private static $fs;
    private static $typeExtensionMap = array(
        'pdf'  => 'pdf',
        'jpg'  => 'image',
        'gif'  => 'image',
        'jpeg' => 'image',
        'png'  => 'image',
        'tiff' => 'image',
        'tif'  => 'image',
    );

    private static $iconMapType = array(
        'doc'  => 'word-o',
        'docx'  => 'word-o',
        'xls'  => 'excel-o',
        'xlsx'  => 'excel-o',
        'zip'  => 'zip-o',
        'jpg'  => 'image-o',
        'gif'  => 'image-o',
        'jpeg' => 'image-o',
        'png'  => 'image-o',
        'tiff' => 'image-o',
        'tif'  => 'image-o',
    );

    public static function getTypeFromMime($mime)
    {
        list($type, $type2) = explode('/', $mime);
        return ($type == 'application') ? $type2 : $type;
    }

    public static function getIconClassForMime($extention)
    {
        return isset(self::$iconMapType[strtolower($extention)]) ? self::$iconMapType[strtolower($extention)] : $extention . '-o';
    }

    public static function formatSize($size)
    {
        $kiloInByte = 1024;

        if ($size <= $kiloInByte) {
            return $size . " B";
        }

        $exp = intval((log($size) / log($kiloInByte)));
        $pre = "KMGTPE";

        $value = number_format($size / pow($kiloInByte, $exp), 2, '.', ',');
        $unit = $pre[$exp - 1];

        return sprintf("%s %sB", $value, $unit);
    }


    public static function removeEmptyParentFolder($path, $endPath)
    {
        if ($path == $endPath) {
            return NULL;
        }

        $di = new \FilesystemIterator($path);

        if (!$di->valid()) {
            $parent = realpath($path . "/..");
            rmdir($path);

            return self::removeEmptyParentFolder($parent, $endPath);
        }

        return FALSE;
    }

    public static function getExtension($file, $changeCase = TRUE)
    {
        $file = new FileObject($file);

        if ($changeCase) {
            return strtolower($file->getExtension());
        } else {
            return $file->getExtension();
        }
    }

    public static function copy($src, $dst, $override = false)
    {
        self::getFileSystem()->copy($src, $dst, $override);
    }

    public static function move($src, $dst, $override = false)
    {
        self::getFileSystem()->rename($src, $dst, $override);
    }

    public static function getTypeByExtension($ext)
    {
        return isset(self::$typeExtensionMap[strtolower($ext)]) ? self::$typeExtensionMap[strtolower($ext)] : $ext;
    }

    public static function getType($file)
    {
        return self::getTypeByExtension(self::getExtension($file, true));
    }

    public static function getMimeType($outputFile)
    {
        return (new SymfonyFile($outputFile))->getMimeType();
    }

    private static function getFileSystem()
    {
        if(self::$fs === NULL) {
            self::$fs = new FileSystem();
        }

        return self::$fs;
    }
}