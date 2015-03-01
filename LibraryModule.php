<?php

/**
 * LibraryModule is the WebModule for site/user libraries containing links or documents and an optional global public library.
 *
 * TODO: Clean up the !$*@%** mess handling the file upload. File dialog should immediately open when clicking the "add document"
 *       button. It should either only allow to upload a single file or it should handle multiple uploaded files gracefully by
 *       creating one document record per file. After uploading the file(s), a form should ask for titles, dates and description
 *       per document.
 * TODO: Uploaded files need to stay bound to the not-yet persisted record in case the record doesn't validate.
 * TODO: When we remove a file from a document it is immediately deleted even if we don't save the document afterwards. Find a 
 *       better way to do this. Best option would be to disallow deletion completely and only allow swapping/updating the file.
 * TODO: Think of a smart way to swap/update the file associated with a document. Do we need a new wall entry for that?
 * TODO: Move items between categories in a space, preferably by drag and drop.
 * TODO: Move items to a foreign library/category a user has access to.
 * TODO: Make the activity sidebar context sensitive. In global public library, it should only display activity concerning public
 *       content of the currently selected space. In a user/space library, it should only display activity concerning any library
 *       content. Stop using the current activity widget or fix the broken links on non-stream pages.
 * TODO: Reference library items from a new post.
 * TODO: Support notifying selected users/groups about a new library item.
 * TODO: i18n of the DeadLibraryLinkValidator failure message.
 *
 * This class is also used to process events catched by the autostart.php listeners.
 *
 * @package humhub.modules.library
 * @since 0.8
 * @author Matthias Wolf
 */
class LibraryModule extends HWebModule
{

    public function init()
    {
        if (!Yii::app() instanceof CConsoleApplication) {
            // this method is called when the module is being created
            // you may place code here to customize the module or the application
            // register script and css files
            $assetPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/resources', true, 0, defined('YII_DEBUG'));
            Yii::app()->clientScript->registerScriptFile($assetPrefix . '/library.js');
            Yii::app()->clientScript->registerCssFile($assetPrefix . '/library.css');
        }
    }

    public function behaviors()
    {
        return array(
            'SpaceModuleBehavior' => array(
                'class' => 'application.modules_core.space.behaviors.SpaceModuleBehavior',
            ),
            'UserModuleBehavior' => array(
                'class' => 'application.modules_core.user.behaviors.UserModuleBehavior',
            ),
        );
    }


    /**
     * Returns module config url.
     *
     * @return String
     */
    public function getConfigUrl()
    {
        return Yii::app()->createUrl('//library/admin');
    }


    /**
     * Returns space module config url.
     *
     * @return String
     */
    public function getSpaceModuleConfigUrl(Space $space)
    {
        return Yii::app()->createUrl('//library/library/config', array(
                    'sguid' => $space->guid,
        ));
    }

    /**
     * Returns the user module config url.
     *
     * @return String
     */
    public function getUserModuleConfigUrl(User $user)
    {
        return Yii::app()->createUrl('//library/library/config', array(
                    'uguid' => $user->guid,
        ));
    }

    /**
     * On global module disable, delete all created content
     */
    public function disable()
    {
        if (parent::disable()) {
            foreach (Content::model()->findAll(array(
                'condition' => 'object_model=:cat OR object_model=:libraryitem',
                'params' => array(':cat' => 'LibraryCategory', ':libraryitem' => 'LibraryItem'))) as $content) {
                $content->delete();
            }
            return true;
        }
        throw new CHttpException(404);
        return false;
    }

    /**
     * Enables this module for a Space.
     */
    public function enableSpaceModule(Space $space)
    {
        if (!$this->isEnabled()) {
            // set default config values
            $this->setDefaultValues($space->container);
        }
        parent::enableSpaceModule($space);
    }

    /**
     * Enables this module for a User.
     */
    public function enableUserModule(User $user)
    {
        if (!$this->isEnabled()) {
            // set default config values
            $this->setDefaultValues($user->container);
        }
        parent::enableUserModule($user);
    }

    /**
     * Initialize Default Settings for a Container.
     * @param HActiveRecordContentContainer $container
     */
    private function setDefaultValues(HActiveRecordContentContainer $container)
    {
        $container->setSetting('enableDeadLinkValidation', 0, 'library');
        $container->setSetting('enableWidget', 0, 'library');
        $container->setSetting('publishersOnly', 0, 'library');
    }

    /**
     * On disabling this module on a space, delete all module -> space related content/data.
     * Method stub is provided by "SpaceModuleBehavior"
     *
     * @param Space $space
     */
    public function disableSpaceModule(Space $space)
    {
        $space->setSetting('enableDeadLinkValidation', 0, 'library');
        $space->setSetting('enableWidget', 0, 'library');
        $space->setSetting('publishersOnly', 0, 'library');
        foreach (LibraryCategory::model()->contentContainer($space)->findAll() as $content) {
            $content->delete();
        }
        foreach (LibraryDocument::model()->contentContainer($space)->findAll() as $content) {
            $content->delete();
        }
        foreach (LibraryLink::model()->contentContainer($space)->findAll() as $content) {
            $content->delete();
        }
    }

    /**
     * On disabling this module on a space, delete all module -> user related content/data.
     * Method stub is provided by "UserModuleBehavior"
     *
     * @param User $user
     */
    public function disableUserModule(User $user)
    {
        foreach (LibraryDocument::model()->contentContainer($user)->findAll() as $content) {
            $content->delete();
        }
        foreach (LibraryLink::model()->contentContainer($user)->findAll() as $content) {
            $content->delete();
        }
        foreach (LibraryCategory::model()->contentContainer($user)->findAll() as $content) {
            $content->delete();
        }
    }

    /**
     * Defines what to do if top menu is initialized.
     * 
     * @param type $event        	
     */
    public static function onTopMenuInit($event)
    {
        $globalPublicLibrary = HSetting::Get('globalPublicLibrary', 'library');
        if ($globalPublicLibrary == 1) {
            $event->sender->addItem(array(
                'label' => Yii::t('LibraryModule.base', 'Library'),
                'url' => Yii::app()->createUrl('//library/global'),
                'target' => '',
                'icon' => '<i class="fa fa-file"></i>',
                'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'library' && Yii::app()->controller->id == 'global'),
                'sortOrder' => 100,
            ));
        }
    }

    /**
     * Defines what to do if admin menu is initialized.
     * 
     * @param type $event        	
     */
    public static function onAdminMenuInit($event)
    {
        $event->sender->addItem(array(
            'label' => Yii::t('LibraryModule.base', 'Library'),
            'url' => Yii::app()->createUrl('//library/admin'),
            'group' => 'manage',
            'icon' => '<i class="fa fa-file"></i>',
            'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'library' && Yii::app()->controller->id == 'admin'),
            'sortOrder' => 410,
        ));
    }

    /**
     * Defines what to do if a spaces sidebar is initialzed.
     * 
     * @param type $event        	
     */
    public static function onSpaceSidebarInit($event)
    {

        $space = Yii::app()->getController()->getSpace();
        if ($space->isModuleEnabled('library')) {
            $event->sender->addWidget('application.modules.library.widgets.LibrarySidebarWidget', array(), array(
                'sortOrder' => 190,
            ));
        }
    }

    /**
     * On build of a Space Navigation, check if this module is enabled.
     * When enabled add a menu item
     *
     * @param type $event        	
     */
    public static function onSpaceMenuInit($event)
    {
        $space = Yii::app()->getController()->getSpace();
        if ($space->isModuleEnabled('library')) {
            $event->sender->addItem(array(
                'label' => Yii::t('LibraryModule.base', 'Library'),
                'url' => Yii::app()->createUrl('/library/library/showLibrary', array('sguid' => $space->guid)),
                'icon' => '<i class="fa fa-file"></i>',
                'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'library')
            ));
        }
    }

    /**
     * On build of a Profile Navigation, check if this module is enabled.
     * When enabled add a menu item
     *
     * @param type $event
     */
    public static function onProfileMenuInit($event)
    {
        $user = Yii::app()->getController()->getUser();

        // Is Module enabled on this workspace?
        if ($user->isModuleEnabled('library')) {
            $event->sender->addItem(array(
                'label' => Yii::t('LibraryModule.base', 'Library'),
                'url' => Yii::app()->createUrl('/library/library/showLibrary', array('uguid' => $user->guid)),
                'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'library'),
            ));
        }
    }

    /**
     * Defines what to do if a spaces sidebar is initialzed.
     *
     * @param type $event
     */
    public static function onProfileSidebarInit($event)
    {
        $user = Yii::app()->getController()->getUser();
        if ($user->isModuleEnabled('library')) {
            $event->sender->addWidget('application.modules.library.widgets.LibrarySidebarWidget', array(), array(
                'sortOrder' => 190,
            ));
        }
    }

}
