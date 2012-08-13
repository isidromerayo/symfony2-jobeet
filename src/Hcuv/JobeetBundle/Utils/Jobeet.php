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
        // replace all non letters or digits by -
        $text = preg_replace('/\W+/', '-', $text);
        // trim and lowercase
        $text = strtolower(trim($text, '-'));
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
}
