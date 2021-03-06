<?php
/**
 * Created by PhpStorm.
 * User: Conrad
 * Date: 4/08/2016
 * Time: 9:50 AM
 */

namespace Language\Fields;

class LocaleSwitcher extends \FormField
{
    protected $locales;

    public function __construct($name, $title = null, $value = null, $locales = [])
    {
        $this->locales = empty($locales) ?
            \TranslateLocale::available_locales() :
            $locales;

        if (empty($title)) {
            $title = 'Change editing locale';
        }

        \Requirements::css('db-translations/css/LocaleSwitcher.css');
        \Requirements::javascript('db-translations/js/LocaleSwitcher.js');

        parent::__construct($name, $title, $value);
    }

    public function Locales()
    {
        $locales = \ArrayList::create();

        foreach ($this->locales as $locale => $label) {
            $locales->push(\ArrayData::create([
                'Locale' => $locale,
                'Label' => $label
            ]));
        }

        return $locales;
    }

    public function CurrentLocale()
    {
        return ($locale = \Controller::curr()->getRequest()->getVar('Locale')) ?
            $locale :
            \TranslateLocale::current_locale();
    }

    public function Field($properties = array()) {
        $context = $this;

        if(count($properties)) {
            $context = $context->customise($properties);
        }

        $this->extend('onBeforeRender', $this);

        return $context->customise(array(
          'Param' => \TranslateLocale::url_param()
        ))->renderWith('LocaleSwitcher');
    }
}