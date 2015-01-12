<?php
/**
 * LibraryAdminForm defines the configurable fields.
 *
 * @package humhub.modules.library.forms
 * @author Matthias Wolf
 */

class LibraryAdminForm extends CFormModel {

    public $globalPublicLibrary;
    public $disclaimerWidget;
    public $disclaimerTitle;
    public $disclaimerContent;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('globalPublicLibrary', 'boolean'),
            array('disclaimerWidget', 'boolean'),
            array('disclaimerTitle', 'type', 'type'=>'string'),
            array('disclaimerContent', 'type', 'type'=>'string'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'globalPublicLibrary' => Yii::t('LibraryModule.base', 'Global public library'),
            'disclaimerWidget' => Yii::t('LibraryModule.base', 'Disclaimer widget'),
            'disclaimerTitle' => Yii::t('LibraryModule.base', 'Disclaimer title'),
            'disclaimerContent' => Yii::t('LibraryModule.base', 'Disclaimer content'),
        );
    }

}
