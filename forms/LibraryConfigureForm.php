<?php
/**
 * LibraryConfigureForm defines the configurable fields.
 *
 * @package humhub.modules.library.forms
 * @author Sebastian Stumpf
 * @author Matthias Wolf
 */
class LibraryConfigureForm extends CFormModel {

    public $enableDeadLinkValidation;
    public $enableWidget;
    public $publishersOnly;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
        		// why do i nee a rule if if i dont haveobne, the value is not saved at all!
        		array('enableDeadLinkValidation', 'boolean', 'falseValue' => 0, 'trueValue' => 1),
        		array('enableWidget', 'boolean', 'falseValue' => 0, 'trueValue' => 1),
        		array('publishersOnly', 'boolean', 'falseValue' => 0, 'trueValue' => 1),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'enableDeadLinkValidation' => Yii::t('LibraryModule.admin', 'Extend link validation by a connection test.'),
            'enableWidget' => Yii::t('LibraryModule.admin', 'Show selected library categories in a sidebar widget.'),
            'publishersOnly' => Yii::t('LibraryModule.admin', 'Only publishers/admins can add items to any category.'),
        );
    }

}
