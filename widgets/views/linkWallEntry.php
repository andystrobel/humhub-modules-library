<?php 
/**
 * View for the wall entry widget. Displays the content shown on the wall if a link is added.
 * 
 * @uses $link the link added to the wall.
 * 
 * @author Sebastian Stumpf
 */
?>

<div class="panel panel-default">
    <div class="panel-body">

        <?php $this->beginContent('application.modules_core.wall.views.wallLayout', array('object' => $link)); ?>

        <div class="media" style="display:table;">

            <a class="pull-left" href="<?php echo $link->href; ?>" target="_blank" style="font-size: 26px; color: #555 !important;">
                <i class="fa fa-link"></i>
            </a>

            <div class="media-body" style="display:table-cell;vertical-align:middle;">
                <h4 class="media-heading" style="margin:0px 0px 1px"><?php echo Yii::t('LibraryModule.base', 'Added a new link %link% to category "%category%".', array('%link%' => '<strong>'.HHtml::link($link->title, $link->href, array('target' => '_blank')).'</strong>', '%category%' => $link->category->title)); ?></h4>
            </div>
        </div>

        <div style="margin-top:5px;">
                <?php
                if ($link->description != null || $link->description != "") {
                    echo $link->description;
                }
                ?>
        </div>

        <?php $this->endContent(); ?>

    </div>
</div>
