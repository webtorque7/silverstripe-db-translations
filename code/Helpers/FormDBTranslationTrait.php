<?php
/**
 * Created by PhpStorm.
 * User: Conrad
 * Date: 24/03/2017
 * Time: 8:14 AM
 */

namespace WebTorque\DBTranslations\Helpers;


trait FormDBTranslationTrait
{
    use \DBTranslationsTrait;
    /**
     * Looks up translations using DBTranslations
     *
     * @param string $formName Name of form to translate
     * @param array $originalMessages Array of messages
     * @return array
     */
    public function translateValidationMessages($formName, $originalMessages)
    {
        $topKey = $formName . 'Validation';
        $translatedMessages = [];

        foreach ($originalMessages as $field => $messages) {
            foreach ($messages as $type => $message) {
                $i18nKey = $field . ucfirst($type);
                $translatedMessages[$field][$type] = $this->TranslatePhrase($topKey . '.' . $i18nKey, $message);
            }
        }

        return $translatedMessages;
    }

    /**
     * Translate the label for a form field
     *
     * @param string $formName Name of form
     * @param string $fieldName Name of field
     * @param string $label Default label for field
     * @param string $params List of fields to be replaced, key followd by replacement
     * @return mixed|string
     */
    public function translateField($formName, $fieldName, $label, ...$params)
    {
        return empty($params) ?
            $this->TranslatePhrase($formName . 'Fields.' . $fieldName, $label) :
            $this->TranslatePhrase($formName . 'Fields.' . $fieldName, $label, $params);
    }
}