<?php
/**
 * Global admin configuration view
 *
 * @author Matthias Wolf
 */
?>

<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('LibraryModule.base', 'Library configuration'); ?></div>
    <div class="panel-body">

        <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'LibraryAdminForm',
                'enableAjaxValidation' => false,
            ));
        ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'globalPublicLibrary'); ?>
            <div class="checkbox">
                <label>
                    <?php echo $form->checkBox($model, 'globalPublicLibrary'); ?> <?php echo Yii::t('LibraryModule.base', 'Enable global public library.'); ?>
                </label>
            </div>
            <p class="help-block"><?php echo Yii::t('LibraryModule.base', 'A public library will be added to the top menu. It contains all public library categories from all spaces.'); ?></p>
            <?php echo $form->error($model, 'globalPublicLibrary'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'disclaimerWidget'); ?>
            <div class="checkbox">
                <label>
                    <?php echo $form->checkBox($model, 'disclaimerWidget'); ?> <?php echo Yii::t('LibraryModule.base', 'Show disclaimer widget in global public library.'); ?>
                </label>
            </div>
            <?php echo $form->textField($model, 'disclaimerTitle', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('disclaimerTitle'))); ?>
            <?php echo $form->textArea($model, 'disclaimerContent', array('class' => 'form-control', 'placeholder' => $model->getAttributeLabel('disclaimerContent'))); ?>

            <p class="help-block"><?php echo Yii::t('LibraryModule.base', 'A disclaimer will be added to the public library.'); ?></p>
            <?php echo $form->error($model, 'globalPublicLibrary'); ?>
        </div>

        <hr>
        <?php echo CHtml::submitButton(Yii::t('base', 'Save'), array('class' => 'btn btn-primary')); ?>
        <a class="btn btn-default" href="<?php echo $this->createUrl('//admin/module'); ?>"><?php echo Yii::t('AdminModule.base', 'Back to modules'); ?></a>

        <?php $this->endWidget(); ?>

    </div>
</div>


