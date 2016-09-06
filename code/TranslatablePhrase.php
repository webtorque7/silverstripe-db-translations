<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/24/2016
 * Time: 10:17 AM
 */
class TranslatablePhrase extends DataObject
{
    private static $db = array(
        'Entity' => 'Varchar(100)',
        'String' => 'Text',
        'Locale' => 'Varchar(10)'
    );

    private static $summary_fields = array(
        'Locale' => 'Locale',
        'Entity' => 'Entity',
        'String' => 'String'
    );

    public function getDefaultSearchContext()
    {
        $context = parent::getDefaultSearchContext();
        $fields = $context->getFields();

        $localeSelector = DropdownField::create(
            "Locale",
            "Locale",
            TranslateLocale::available_locales()
        );
        $localeSelector->setEmptyString('All');

        $fields->insertAfter($localeSelector, 'String');

        $filters = $context->getFilters();
        $filters['Locale'] = ExactMatchFilter::create('TranslatablePhrase.Locale');

        $context->setFilters($filters);

        return $context;
    }
}