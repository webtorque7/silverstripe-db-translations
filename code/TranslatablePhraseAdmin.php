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

    public function init()
    {
        parent::init();

        Requirements::css('db-translations/css/TranslationsForm.css');
    }

    public function getEditForm($id = null, $fields = null)
    {
        return $this->TranslatablePhraseForm();
    }

    public function TranslatablePhraseForm()
    {


        $form = TranslatablePhraseForm::create($this, 'TranslatablePhraseForm');
        $form->setAttribute('data-pjax-fragment', 'CurrentForm');
        $form->setResponseNegotiator($this->getResponseNegotiator());
        $form->addExtraClass('cms-content center cms-edit-form');

        return $form;
    }
}