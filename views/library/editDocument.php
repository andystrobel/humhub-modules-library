<?php 
/**
 * View to edit a document.
 * 
 * @uses $document the document object.
 * @uses $isCreated true if the document is first created, false if an existing document is edited
 * 
 * @author Sebastian Stumpf
 * @author Matthias Wolf
 * 
 */
?>


<div class="panel panel-default">
    <div class="panel-heading">
    <?php if($isCreated) {
        echo Yii::t('LibraryModule.base', '<strong>Create</strong> new document');
    } else {
        echo Yii::t('LibraryModule.base', '<strong>Edit</strong> document');
    } ?>
    </div>
    <div class="panel-body">
        <?php
        $form = $this->beginWidget('HActiveForm', array(
            'id' => 'document-edit-form',
            'enableAjaxValidation' => false,
        ));
        echo $form->errorSummary($document);
        ?>

            <div class="form-group">
                <?php echo $form->labelEx($document, 'title'); ?>
                <?php echo $form->textField($document, 'title', array('class' => 'form-control')); ?>
                <?php echo $form->error($document, 'title'); ?>
            </div>

            <div class="form-group">
            <?php
            echo '<label>' . Yii::t('LibraryModule.base', 'File') . ' *</label><br/>';
                // Creates Uploading Button
                $this->widget('application.modules_core.file.widgets.FileUploadButtonWidget', array(
                    'uploaderId' => 'document_upload_' . $document->id,
                    'fileListFieldName' => 'fileList',
                    'object' => $document
                ));
                // Creates a list of already uploaded Files
                $this->widget('application.modules_core.file.widgets.FileUploadListWidget', array(
                    'uploaderId' => 'document_upload_' . $document->id,
                    'object' => $document
                ));
                echo $form->error($document, 'file');
                echo '<p class="help-block">' . Yii::t('LibraryModule.base', 'If you upload multiple files, only the last file will be saved.') . '</p>';
            ?>
	    </div>

	    <div class="form-group">
		<?php //print_r($document); die(); ?>
	        <?php echo $form->labelEx($document, 'date'); ?>
	        <?php echo $form->dateTimeField($document, 'date', array('class' => 'form-control')); ?>
	        <?php echo $form->error($document, 'date'); ?>
	    </div>
	
	    <div class="form-group">
	        <?php echo $form->labelEx($document, 'description'); ?>
	        <?php echo $form->textArea($document, 'description', array('class' => 'form-control', 'rows' => '2')); ?>
	        <?php echo $form->error($document, 'description'); ?>
	    </div>
	
	    <div class="form-group">
            <?php
            ?>
	    </div>

	    <!--<div class="form-group">-->
	        <?php //echo $form->labelEx($document, 'href'); ?>
	        <?php //echo $form->textField($document, 'href', array('class' => 'form-control')); ?>
	        <?php //echo $form->error($document, 'href'); ?>
	    <!--</div>-->


	    <!--<div class="form-group">-->
	        <?php //echo $form->labelEx($document, 'sort_order'); ?>
	        <?php //echo $form->numberField($document, 'sort_order', array('class' => 'form-control')); ?>
	        <?php //echo $form->error($document, 'sort_order'); ?>
	    <!--</div>-->
	

        <?php echo CHtml::submitButton(Yii::t('LibraryModule.base', 'Save'), array('class' => 'btn btn-primary')); ?>
        <a class="btn btn-default" href="<?php echo Yii::app()->getController()->libraryUrl?>"><?php echo Yii::t('LibraryModule.base', 'Back to library'); ?></a>

        <?php $this->endWidget(); ?>
    </div>
</div>
