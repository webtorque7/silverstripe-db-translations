<?php

/**
 * Created by PhpStorm.
 * User: User
 * Date: 8/25/2016
 * Time: 11:23 AM
 */
class TranslatablePhraseAdmin extends LeftAndMain
{
    public static $menu_title = 'Translatable Phrases';
    public static $url_segment = 'translatable-phrase';
    public static $managed_models = array('TranslatablePhrase');

    private static $allowed_actions = array(
        'TranslatablePhraseForm'
    );

    public function getEditForm($id = null, $fields = null)
    {
        Requirements::css('db-translations/css/TranslationsForm.css');
        Requirements::javascript('db-translations/js/TranslationsForm.js');

        return $this->TranslatablePhraseForm()->addExtraClass('cms-edit-form cms-panel-padded center');
    }

    public function TranslatablePhraseForm()
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/mysite/lang/en.yml';
        $array = $this->loadFieldsFromYML($path);
        $phrases = array();
        if (!empty($array) && isset($array['en'])) {
            $defaultFields = $array['en'];
            $phrases = $this->flatten($defaultFields);
        }

        $form = TranslatablePhraseForm::create($this, 'TranslatablePhraseForm', $phrases);
        return $form;
    }

    public function loadFieldsFromYML($path)
    {
        if (file_exists($path)) {
            $file = file_get_contents($path);
            $ymlFields = sfYaml::load($file);

            return $ymlFields;
        }
    }

    public function flatten($array, $prefix = '')
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