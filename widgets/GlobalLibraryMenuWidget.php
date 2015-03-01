<?php

/**
 * The LibraryMenuWidget constructs the navigation menu of the global public library.
 *
 * @package humhub.modules.library
 * @author Matthias Wolf
 */
class GlobalLibraryMenuWidget extends MenuWidget
{

    public $template = "application.widgets.views.leftNavigation";
    public $type = "libraryNavigation";

    public function init()
    {

        $this->addItemGroup(array(
            'id' => 'spaces',
            'label' => Yii::t('LibraryModule.base', '<strong>Public</strong> libraries'),
            'sortOrder' => 100,
        ));

        if (is_array(Yii::app()->controller->publicLibraries)) {

            // Get array of public libraries from controller and sort descending.
            $publicLibraries = Yii::app()->controller->publicLibraries;
            krsort($publicLibraries, SORT_NUMERIC);

            // Loop array and add menu entries so we end up with the lowest ID on top.
            foreach ($publicLibraries as $id => $library) {
                $this->addItem(array(
                    'label' => $library,
                    'url' => Yii::app()->createUrl('library/global/showLibrary', array('id' => $id)),
                    'group' => 'spaces',
                    'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'library' && Yii::app()->controller->id == 'global' && Yii::app()->controller->action->id == 'showLibrary' && Yii::app()->controller->currentLibrary == $id),
                ));
            }
        }

        parent::init();
    }

}

?>
