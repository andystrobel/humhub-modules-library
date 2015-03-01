<?php 
Yii::app()->moduleManager->register(array(
    'id' => 'library',
    'class' => 'application.modules.library.LibraryModule',
    'import' => array(
        'application.modules.library.*',
        'application.modules.library.models.*',
        'application.modules.library.views.*',
        'application.modules.library.controllers.*',
        'application.modules.library.components.*',
    ),
    // Events to Catch 
    'events' => array(
        array('class' => 'TopMenuWidget', 'event' => 'onInit', 'callback' => array('LibraryModule', 'onTopMenuInit')),
        array('class' => 'AdminMenuWidget', 'event' => 'onInit', 'callback' => array('LibraryModule', 'onAdminMenuInit')),
        array('class' => 'SpaceMenuWidget', 'event' => 'onInit', 'callback' => array('LibraryModule', 'onSpaceMenuInit')),
        array('class' => 'ProfileMenuWidget', 'event' => 'onInit', 'callback' => array('LibraryModule', 'onProfileMenuInit')),
        array('class' => 'SpaceSidebarWidget', 'event' => 'onInit', 'callback' => array('LibraryModule', 'onSpaceSidebarInit')),
        array('class' => 'ProfileSidebarWidget', 'event' => 'onInit', 'callback' => array('LibraryModule', 'onProfileSidebarInit')),
    ),
));
?>
