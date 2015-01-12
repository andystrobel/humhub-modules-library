<?php 
/**
 * View for the wall entry widget. Displays the content shown on the wall if a document is added.
 * 
 * @uses $document the document added to the wall.
 * 
 * @author Matthias Wolf
 */
?>

<div class="panel panel-default">
    <div class="panel-body">
	
	<div class="library-document">

        <?php $this->beginContent('application.modules_core.wall.views.wallLayout', array('object' => $document)); ?>
        
        <div class="media" style="display:table;">
            <div class="pull-left">
                <i class="fa fa-file" style="font-size: 26px; color: #555 !important;"></i>
            </div>

            <div class="media-body" style="display:table-cell;vertical-align:middle;">
                <h4 class="media-heading" style="margin:0px 0px 1px"><?php echo Yii::t('LibraryModule.base', 'Added a new document %document% to category "%category%".', array('%document%' => '<strong>'.$document->title.'</strong>', '%category%' => $document->category->title)); ?></h4>
            </div>
        </div>

        <div style="margin-top:5px;">
                <?php
                if ($document->description != null || $document->description != "") {
                    echo $document->description;
                }
                ?>
        </div>

        <?php $this->endContent(); ?>

	</div>

    </div>
</div>