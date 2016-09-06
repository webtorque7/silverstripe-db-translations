<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 9/6/2016
 * Time: 10:47 AM
 */
interface LocalisationService
{
    public function current_locale();
    public function available_locales();
}