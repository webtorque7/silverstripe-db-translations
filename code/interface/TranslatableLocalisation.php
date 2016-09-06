<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/6/2016
 * Time: 11:00 AM
 */

class TranslatableLocalisation implements LocalisationService
{
    public function current_locale()
    {
        return Translatable::get_current_locale();
    }

    public function available_locales()
    {
        return Translatable::get_existing_content_languages();
    }
}