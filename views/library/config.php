<?php 
/**
 * Library configuration view.
 * 
 * @uses $form the form with the formular fields.
 * 
 * @author Sebastian Stumpf
 * @author Matthias Wolf
 * 
 */
?>

<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('LibraryModule.base', 'Library Module Configuration'); ?></div>
    <div class="panel-body">

        <p><?php echo Yii::t('LibraryModule.base', 'You can configure extended functionality of this module for a space or user.'); ?></p><br />

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'library-configure-form',
            'enableAjaxValidation' => false,
        ));
        ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="form-group">
            <div class="checkbox">
                <label>
                    <?php echo $form->checkBox($model, 'enableDeadLinkValidation'); ?> <?php echo $model->getAttributeLabel('enableDeadLinkValidation'); ?>
                </label>
            </div>
        </div>
        <?php echo $form->error($model, 'enableDeadLinkValidation'); ?>
        
        <div class="form-group">
            <div class="checkbox">
                <label>
                    <?php echo $form->checkBox($model, 'publishersOnly'); ?> <?php echo $model->getAttributeLabel('publishersOnly'); ?>
                </label>
            </div>
        </div>
	<?php echo $form->error($model, 'publishersOnly'); ?>

        <div class="form-group">
            <div class="checkbox">
                <label>
                    <?php echo $form->checkBox($model, 'enableWidget'); ?> <?php echo $model->getAttributeLabel('enableWidget'); ?>
                </label>
            </div>
        </div>
	<?php echo $form->error($model, 'enableWidget'); ?>
        
        <hr>
        <?php echo CHtml::submitButton(Yii::t('LibraryModule.base', 'Save'), array('class' => 'btn btn-primary')); ?>
        <a class="btn btn-default" href="<?php echo Yii::app()->getController()->modulesUrl?>"><?php echo Yii::t('AdminModule.base', 'Back to modules'); ?></a>
        <?php $this->endWidget(); ?>
    </div>
</div>