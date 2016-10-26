<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/24/2016
 * Time: 3:50 PM
 */
class DBTranslationsControllerExtension extends Extension
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