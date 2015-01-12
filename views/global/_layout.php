<?php
/**
 * Layout for library module global view
 *
 * @package humhub.modules.library.views
 *
 * @author Matthias Wolf
 */

?>

<div class="container">

    <div class="row">
        <div class="col-md-2">
            <!-- show library menu widget -->
            <?php $this->widget('application.modules.library.widgets.GlobalLibraryMenuWidget', array()); ?>
        </div>
        <div class="col-md-7">
            <!-- show content -->
            <?php echo $content; ?>
        </div>
        <div class="col-md-3">
            <!-- show directory sidebar stream -->
            <?php

            //$this->widget('application.modules.library.widgets.GlobalLibrarySidebarWidget', array(
            $this->widget('application.widgets.StackWidget', array(
                    'widgets' => array(
                        array('application.modules.library.widgets.DisclaimerWidget', array(), array('sortOrder' => 10)),
                        array('application.modules_core.activity.widgets.ActivityStreamWidget', array('type' => Wall::TYPE_DASHBOARD), array('sortOrder' => 20)),
                    )
            ));


            ?>
        </div>
    </div>

</div>
