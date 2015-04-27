<?php
/* @var $this AutoschoolController */
/* @var $model Autoschool */
/* @var $form CActiveForm */
?>
<?php if($model->isNewRecord==false){ ?>
<?php $this->widget('bootstrap.widgets.TbNav', array(
    'type' => TbHtml::NAV_TYPE_TABS,
    'items' => array(
        array('label' => 'Общая информация', 'url' => '#', 'active' => true),
        array('label' => 'Практика', 'url' => Yii::app()->createUrl('/admin/autoschool/updatepractice',array('id'=>$model->id)), ),
    ),
)); ?>
<?php } ?>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'autoschool-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> - обязательные поля.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'contacts'); ?>
        <?php $this->widget('application.extensions.eckeditor.ECKEditor', array(
            'model'=>$model,
            'attribute'=>'contacts',
        )); ?>
		<?php echo $form->error($model,'contacts'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'info'); ?>
        <?php $this->widget('application.extensions.eckeditor.ECKEditor', array(
            'model'=>$model,
            'attribute'=>'info',
        )); ?>
		<?php echo $form->error($model,'info'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'price'); ?>
        <?php $this->widget('application.extensions.eckeditor.ECKEditor', array(
            'model'=>$model,
            'attribute'=>'price',
        )); ?>
        <?php echo $form->error($model,'price'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'studentCode'); ?>
        <?php echo $form->textField($model,'studentCode',array('size'=>6, 'maxlenght'=>50)); ?>
        <?php echo $form->error($model,'studentCode'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'teacherCode'); ?>
        <?php echo $form->textField($model,'teacherCode',array('size'=>6, 'maxlenght'=>50)); ?>
        <?php echo $form->error($model,'teacherCode'); ?>
    </div>

	<div class="row buttons">
		<?php echo TbHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->