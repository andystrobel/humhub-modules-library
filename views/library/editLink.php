<?php 
/**
 * View to edit a link category.
 * 
 * @uses $link the link object.
 * @uses $isCreated true if the link is first created, false if an existing link is edited
 * 
 * @author Sebastian Stumpf
 * @author Matthias Wolf
 * 
 */
?>


<div class="panel panel-default">
    <div class="panel-heading">
    <?php if($isCreated) {
        echo Yii::t('LibraryModule.base', '<strong>Create</strong> new link');
    } else {
        echo Yii::t('LibraryModule.base', '<strong>Edit</strong> link');
    } ?>
    </div>
    <div class="panel-body">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'library-link-edit-form',
            'enableAjaxValidation' => false,
        ));
	echo $form->errorSummary($link); 
	?>

	    <div class="form-group">
	        <?php echo $form->labelEx($link, 'title'); ?>
	        <?php echo $form->textField($link, 'title', array('class' => 'form-control')); ?>
	        <?php echo $form->error($link, 'title'); ?>
	    </div>
	
	    <div class="form-group">
	        <?php echo $form->labelEx($link, 'description'); ?>
	        <?php echo $form->textArea($link, 'description', array('class' => 'form-control', 'rows' => '2')); ?>
	        <?php echo $form->error($link, 'description'); ?>
	    </div>
	
	    <div class="form-group">
	        <?php echo $form->labelEx($link, 'href'); ?>
	        <?php echo $form->textField($link, 'href', array('class' => 'form-control')); ?>
	        <?php echo $form->error($link, 'href'); ?>
	    </div>

        <?php echo CHtml::submitButton(Yii::t('LibraryModule.base', 'Save'), array('class' => 'btn btn-primary')); ?>
        <a class="btn btn-default" href="<?php echo Yii::app()->getController()->libraryUrl?>"><?php echo Yii::t('LibraryModule.base', 'Back to library'); ?></a>

        <?php $this->endWidget(); ?>
    </div>
</div>