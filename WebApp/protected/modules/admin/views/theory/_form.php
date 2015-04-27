<?php
/* @var $this TheoryController */
/* @var $model Theory */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'theory-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> - обязательные поля.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'teacher_id'); ?>
        <?php echo $form->dropDownList($model,'teacher_id',CHtml::listData($teachers,'id','name'),array('empty' => '(Выберите преподователя)'));?>
		<?php echo $form->error($model,'teacher_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'group_id'); ?>
        <?php echo $form->dropDownList($model,'group_id',CHtml::listData($groups,'group_id','name'),array('empty' => '(Выберите группу)'));?>
		<?php echo $form->error($model,'group_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'room'); ?>
		<?php echo $form->textField($model,'room',array('size'=>5,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'room'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start_time'); ?>
        <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
        $this->widget('CJuiDateTimePicker',array(
            'model'=>$model, //Model object
            'attribute'=>'start_time', //attribute name
            'mode'=>'datetime', //use "time","date" or "datetime" (default)
            'options'   => array(
                'dateFormat' => 'yy-mm-dd',
                'timeFormat' => 'hh:mm:ss',
            ),
        ));?>
		<?php echo $form->error($model,'start_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'end_time'); ?>
        <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
        $this->widget('CJuiDateTimePicker',array(
            'model'=>$model, //Model object
            'attribute'=>'end_time', //attribute name
            'mode'=>'datetime', //use "time","date" or "datetime" (default)
            'options'   => array(
                'dateFormat' => 'yy-mm-dd',
                'timeFormat' => 'hh:mm:ss',
            ),
        ));?>
		<?php echo $form->error($model,'end_time'); ?>
	</div>

	<div class="row buttons">
		<?php echo TbHtml::submitButton($model->isNewRecord ? 'Создать' : 'Соранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->