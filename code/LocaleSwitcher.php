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
            \Fluent::Config()->aliases :
            $locales;

        if (empty($title)) {
            $title = 'Change editing locale';
        }

        \Requirements::css('language-module/css/LocaleSwitcher.css');
        \Requirements::javascript('language-module/js/LocaleSwitcher.js');

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
        return ($locale = \Controller::curr()->getRequest()->getVar('locale')) ?
            $locale :
            \Fluent::current_locale();
    }

    public function Field($properties = array()) {
        $context = $this;

        if(count($properties)) {
            $context = $context->customise($properties);
        }

        $this->extend('onBeforeRender', $this);

        return $context->renderWith('LocaleSwitcher');
    }
}