<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/6/2016
 * Time: 10:57 AM
 */
class FluentLocalisation implements LocalisationService
{
    public function current_locale()
    {
        return Fluent::current_locale();
    }

    public function available_locales()
    {
        return Fluent::Config()->aliases;
    }

    public function url_param(){
        return 'l';
    }
}