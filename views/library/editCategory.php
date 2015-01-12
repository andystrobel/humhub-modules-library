<?php 
/**
 * View to edit a document category.
 * 
 * @uses $category the category object.
 * @uses $isCreated true if the category is first created, false if an existing category is edited.
 * 
 * @author Sebastian Stumpf
 * @author Matthias Wolf
 * 
 */
?>


<div class="panel panel-default">
    <div class="panel-heading">
    <?php if($isCreated) {
    echo Yii::t('LibraryModule.base', '<strong>Create</strong> new category');
    } else {
    echo Yii::t('LibraryModule.base', '<strong>Edit</strong> category');
    } ?>
    </div>
    <div class="panel-body">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'category-edit-form',
            'enableAjaxValidation' => false,
        ));
		//echo $form->errorSummary($category); ?>

	    <div class="form-group">
	        <?php echo $form->labelEx($category, 'title'); ?>
	        <?php echo $form->textField($category, 'title', array('class' => 'form-control')); ?>
	        <?php echo $form->error($category, 'title'); ?>
	    </div>
	    
	    <div class="form-group">
	        <?php echo $form->labelEx($category, 'description'); ?>
	        <?php echo $form->textArea($category, 'description', array('class' => 'form-control', 'rows' => 3)); ?>
	        <?php echo $form->error($category, 'description'); ?>
	    </div>
	    
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <?php echo $form->checkBox($category, 'show_sidebar'); ?> <?php echo Yii::t('LibraryModule.base', 'Show this category in sidebar widget.'); ?>
                    </label>
                </div>
                <?php echo $form->error($category, 'show_sidebar'); ?>
            </div>
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <?php echo $form->checkBox($category->content, 'visibility'); ?> <?php echo Yii::t('LibraryModule.base', 'This is a public category.'); ?>
                    </label>
                    <div><i><?php echo Yii::t('LibraryModule.base', 'All users can access items in public categories. Only publishers can add items.'); ?></i></div>
                </div>
                <?php echo $form->error($category->content, 'visibility'); ?>
            </div>

	    
        <?php echo CHtml::submitButton(Yii::t('LibraryModule.base', 'Save'), array('class' => 'btn btn-primary')); ?>
        <a class="btn btn-default" href="<?php echo Yii::app()->getController()->libraryUrl?>"><?php echo Yii::t('LibraryModule.base', 'Back to library'); ?></a>
                
        <?php $this->endWidget(); ?>
    </div>
</div>
