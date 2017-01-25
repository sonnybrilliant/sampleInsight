<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/10/02
 */
namespace AppBundle\Common;


class FileUtil
{
    const LENGTH = 25;
    /**
     * Get Human readable file size
     *
     * @param $bytes
     * @param int $decimals
     * @return string
     */
    public static function getHumanFileSize($bytes, $decimals = 2)
    {
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
    }

    /**
     * Get class name (Without name space)
     * @param $object
     * @return mixed
     */
    public static function getClassName($object)
    {
        $class = explode('\\', $object);
        return end($class);
    }

    /**
     * Shorten string
     * @param $str
     * @param int $limit
     * @return string
     */
    public static function strEllipsis($str,$repl = '...' ,$limit = 20)
    {
        if(strlen($str) > $limit)
        {
            return substr($str, 0, $limit) .$repl;
        }
        else
        {
            return $str;
        }
    }

    /**
     * Get a sum of plays for Artist / Song
     * @param $arr
     * @return float|int
     */
    public static function sumArrayCountArtistSong($arr)
    {
        return array_sum(array_map(function($item) {
            return $item['played'];
        }, $arr));
    }
}