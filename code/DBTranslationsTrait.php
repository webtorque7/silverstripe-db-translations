<?php

/**
 * Created by PhpStorm.
 * User: Conrad
 * Date: 26/10/2016
 * Time: 1:55 PM
 */
trait DBTranslationsTrait
{
    public function TranslatePhrase($entity, $string, ...$params)
    {
        $injection = array();

        while (count($params)) {
            list($key, $value) = array_splice($params, 0, 2);
            $injection[$key] = $value;
        }

        return TranslateService::translate($entity, $string, $injection);
    }
}