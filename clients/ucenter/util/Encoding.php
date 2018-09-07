<?php
/**
 * Encoding class file
 *
 * All rights reserved.
 *
 * PHP version 5
 *
 * @category System
 * @package  ucenter
 * @license  http://api.ucenter.server.com/license  Software Distribution
 * @link     http://api.ucenter.server.com/docs/index.html
 */

/**
 * The Encoding classes lists the character encodings we supported currently
 *
 * @category System
 * @package  ucenter
 */
namespace clients\ucenter\util;


class Encoding
{
    const ASCII = 'ASCII';
    const UTF8 = 'UTF-8';
    const GBK = 'GBK';
    const GB2312 = 'GB2312';

    /**
     * 给内容转码
     *
     * @param string $source 字符串
     * @param string $in     原始编码
     * @param string $out    要转的编码
     *
     * @return string
     */
    public static function convert($source, $in, $out)
    {
        $in = strtoupper($in);
        $out = strtoupper($out);
        if ($in == "UTF8") {
            $in = self::UTF8;
        }
        if ($out == "UTF8") {
            $out = self::UTF8;
        }
        if ($in==$out) {
            return $source;
        }
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($source, $out, $in);
        } elseif (function_exists('iconv')) {
            return iconv($in, $out."//IGNORE", $source);
        }
        return $source;
    }
}