<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/6/2016
 * Time: 11:35 AM
 */
class TranslateLocale extends Object
{
    public static function current_locale(){
        $class = self::config()->class;
        $localisation = new $class();
        return $localisation->current_locale();
    }

    public static function available_locales(){
        $class = self::config()->class;
        $localisation = new $class();
        return $localisation->available_locales();
    }
}