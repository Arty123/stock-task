<?php
/**
 * Created by PhpStorm.
 * User: a.abelyan
 * Date: 11.08.2017
 * Time: 15:56
 */

namespace AppBundle\Service;


class Converter
{
    /**
     * @param $data
     */
    public function convertCharset(&$data)
    {
        // Stock.csv has ASCII charset and so we need to encode all of data in ASCII
        // Some products has strange symbols in name field in UTF-8 charset
        // Convert of this fields gives symbol '?', and I don't know what I should to do with it
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = trim(mb_convert_encoding($data[$i], 'ASCII', 'auto'));
        }
    }
}