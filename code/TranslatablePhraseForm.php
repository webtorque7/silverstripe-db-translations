<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/25/2016
 * Time: 11:36 AM
 */
class TranslatablePhraseForm extends CMSForm
{
    protected $translations = [];

    /**
     * TranslatablePhraseForm constructor.
     * @param Controller $controller
     * @param string $name
     * @param array $translations
     * @param array $locales
     */
    public function __construct($controller, $name = 'TranslatablePhraseForm', $locales = [])
    {
        $fields = FieldList::create();

        $actions = FieldList::create(array(
            FormAction::create('updateTranslatable', _t('CMSMain.SAVE', 'Save'))
                ->setUseButtonTag(true)
                ->addExtraClass('ss-ui-action-constructive')->setAttribute('data-icon', 'accept')
        ));

        parent::__construct($controller, $name, $fields, $actions);

        $this->loadTranslations();
        $this->populateFields();
    }

    /**
     * Populate all the fields from the translations, resets any fields already there
     */
    protected function populateFields()
    {
        $currentLocale = TranslateLocale::current_locale();

        $fields = FieldList::create(TabSet::create('Root', Tab::create('Main')));

        $fields->push(HiddenField::create('Locale', 'Locale', $currentLocale));
        $fields->addFieldToTab('Root.Main', Language\Fields\LocaleSwitcher::create('LocaleSwitcher'));

        $groups = [];

        TranslateService::flush();

        foreach ($this->translations as $entity => $translation) {
            list($group, $key) = explode('.', $entity);

            $groups[$group][$key] = TextField::create($entity, $this->niceLabel($key))
                ->setValue(TranslateService::lookup_translation($entity, $currentLocale))
                ->setDescription(Convert::raw2xml(TranslateService::translate($entity, $translation, null, 'en')));
        }

        foreach ($groups as $groupName => $subFields) {
            $fields->addFieldToTab('Root.Main', ToggleCompositeField::create($groupName, $groupName, $subFields));
        }

        $this->setFields($fields);
    }

    /**
     * Split camelCase and TitleCase labels into titles, all uppercase eg BUTTONLOSTPASSWORD should be treated as 1 word
     *
     * @param $label
     * @return string
     */
    public function niceLabel($label)
    {
        $parts = preg_split("/(?<=[a-z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z])/", $label);

        if (!empty($parts)) {
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

        try {
            foreach ($data as $key => $value) {
                //undo SilverStripe string replace
                $entity = str_replace('_', '.', $key);
                $phrase = TranslatablePhrase::get()->filter(array('Locale' => $locale, 'Entity' => $entity))->first();
                if ($phrase) {
                    $phrase->String = $value;
                    $phrase->Entity = $entity;
                    $phrase->write();
                } else {
                    $phrase = TranslatablePhrase::create();
                    $phrase->Locale = $locale;
                    $phrase->Entity = $entity;
                    $phrase->String = $value;
                    $phrase->write();
                }
            }

        } catch (ValidationException $ex) {
            $this->sessionMessage($ex->getResult()->message(), 'bad');
            return $this->getResponseNegotiator()->respond($this->controller->getRequest());
        }

        $this->controller->getResponse()->addHeader('X-Status', rawurlencode(_t('LeftAndMain.SAVEDUP', 'Saved.')));

        $this->populateFields();

        return $this->forTemplate();
    }

    protected function loadTranslations()
    {
        $path = Director::baseFolder() . '/mysite/lang/en.yml';
        $array = $this->loadFieldsFromYML($path);
        $phrases = array();
        if (!empty($array) && isset($array['en'])) {
            $defaultFields = $array['en'];
            $phrases = $this->flatten($defaultFields);
        }

        $this->translations = $phrases;
    }

    protected function loadFieldsFromYML($path)
    {
        if (file_exists($path)) {
            $file = file_get_contents($path);
            $ymlFields = sfYaml::load($file);

            return $ymlFields;
        }
    }

    protected function flatten($array, $prefix = '')
    {
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result += $this->flatten($value, $prefix . $key . '.');
            } else {
                $result[$prefix . $key] = $value;
            }
        }

        return $result;
    }
}