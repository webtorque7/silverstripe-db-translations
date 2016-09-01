<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/25/2016
 * Time: 11:17 AM
 */
class TranslateService
{
    private static $translations = [];

    public static function translate($entity, $string, $injection = null, $locale = null)
    {
        $currentLocale = $locale ?: Fluent::current_locale();

        // if no string being passed through use string from db
        if((!$string || $string == '')){
            $string = self::lookup_translation($entity, $currentLocale);
        }

        // swap out variables within the string
        if($string != ''){
            $string = preg_replace_callback('/{(.+?)}/u', function ($matches) use ($injection){
                return !empty($injection[$matches[1]]) ? $injection[$matches[1]] : $matches[0];
            }, $string);

            return $string;
        }

        // if no string can be found in database fall back to default translation
        return i18n::_t($entity, $string, '', $injection);
    }

    public static function lookup_translation($entity, $locale)
    {
        if (empty(self::$translations[$locale])) {

            $phrases = TranslatablePhrase::get()
                ->filter(array('Locale' => $locale))
                ->map('Entity', 'String')
                ->toArray();

            self::$translations[$locale] = $phrases;
        }

        return !empty(self::$translations[$locale][$entity]) ? self::$translations[$locale][$entity] : '';
    }
}