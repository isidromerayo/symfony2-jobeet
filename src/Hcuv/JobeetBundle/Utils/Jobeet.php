<?php
/**
 * Created by JetBrains PhpStorm.
 * User: imerayo
 * Date: 8/9/12
 * Time: 10:53 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Hcuv\JobeetBundle\Utils;

class Jobeet
{
    /**
     * Slugify text into lowercase
     *
     * @param $text
     * @return mixed|string
     */
    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('#[^\\pL\d]+#u', '-', $text);
        // trim
        $text = trim($text, '-');
        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('#[^-\w]+#', '', $text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
}
