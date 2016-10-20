<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/25/2016
 * Time: 11:36 AM
 */
class TranslatablePhraseForm extends CMSForm
{
    /**
     * TranslatablePhraseForm constructor.
     * @param Controller $controller
     * @param string $name
     * @param array $translations
     * @param array $locales
     */
    public function __construct($controller, $name = 'TranslatablePhraseForm', $translations = [], $locales = [])
    {
        $fields = FieldList::create(array(
            Language\Fields\LocaleSwitcher::create('LocaleSwitcher')
        ));

        $groups = [];
        foreach ($translations as $entity => $translation) {
            list($group, $key) = explode('.', $entity);

            $groups[$group][$key] = TextField::create($entity, $this->niceLabel($key))
                ->setValue(TranslateService::lookup_translation($entity, TranslateLocale::current_locale()))
                ->setDescription(TranslateService::translate($entity, $translation, 'en'));
        }

        foreach ($groups as $groupName => $subFields) {
            $fields->push(ToggleCompositeField::create($groupName, $groupName, $subFields));
        }

        $actions = FieldList::create(array(
            FormAction::create('updateTranslatable', 'Update Translatable Phrases')
        ));

        parent::__construct($controller, $name, $fields, $actions);
    }

    /**
     * Split camelCase and TitleCase labels into titles, all uppercase eg BUTTONLOSTPASSWORD should be treated as 1 word
     *
     * @param $label
     * @return string
     */
    public function niceLabel($label){
        $parts = preg_split("/(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z])/", $label);

        if(!empty($parts)){
            return implode(" ", $parts);
        }

        return $label;
    }

    /**
     * Form Action
     *
     * @param $data
     * @param Form $form
     */
    public function updateTranslatable($data, Form $form)
    {
        $nonTranslationData = array('url', 'SecurityID', 'action_updateTranslatable', 'BackURL');
        $data = array_diff_key($data, array_flip($nonTranslationData));

        $locale = $data['Locale'];

        foreach($data as $key => $value){
            //undo SilverStripe string replace
            $entity = str_replace('_', '.', $key);
            $phrase = TranslatablePhrase::get()->filter(array('Locale' => $locale, 'Entity' => $entity))->first();
            if($phrase && $phrase->Exists()){
                $phrase->String = $value;
                $phrase->write();
            }
            else{
                $phrase = TranslatablePhrase::create();
                $phrase->Locale = $locale;
                $phrase->Entity = $entity;
                $phrase->String = $value;
                $phrase->write();
            }
        }
    }
}