<?php
/* @var $this GroupController */
/* @var $model Group */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'group-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> - обязательные поля.</p>

	<?php echo $form->errorSummary($model); ?>
    <?php if(Yii::app()->user->role == "administrator"): ?>
    <div class="row">
        <?php echo $form->labelEx($model,'autoschool_id'); ?>
        <?php echo $form->dropDownList($model,'autoschool_id',CHtml::listData(Autoschool::model()->findall(),'id','name'),array('empty' => '(Выберите школу)'));?>
        <?php echo $form->error($model,'autoschool_id'); ?>
    </div>
    <?php endif; ?>
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
<?php if($model->isNewRecord==false){ ?>
    <div class="row">
        <?php echo $form->labelEx($model,'practice_teacher'); ?>
        <?php echo $form->dropDownList($model,'practice_teacher',CHtml::listData(User::GetSchoolTeachers($model->autoschool_id),'id','name'),array('empty' => '(Выберите преподователя)'));?>
        <?php echo $form->error($model,'practice_teacher'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'practice_start'); ?>
        <?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
        $this->widget('CJuiDateTimePicker',array(
            'model'=>$model, //Model object
            'attribute'=>'practice_start', //attribute name
            'mode'=>'datetime', //use "time","date" or "datetime" (default)
            'options'   => array(
                'dateFormat' => 'yy-mm-dd',
                'showHour'=>false,
                'showMinute'=>false,
            ),
        ));?>
        <?php echo $form->error($model,'practice_start'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model,'practice_meetpoint'); ?>
        <?php echo $form->textField($model,'practice_meetpoint',array('size'=>50,'maxlength'=>50)); ?>
        <?php echo $form->error($model,'practice_meetpoint'); ?>
    </div>
    <?php for($i=2;$i<21;$i++) $days[$i]=$i; ?>
    <div class="row">
        <?php echo $form->labelEx($model,'practice_days'); ?>
        <?php echo $form->dropDownList($model,'practice_days',$days);?>
        <?php echo $form->error($model,'practice_days'); ?>
    </div>
    <?php for($i=1;$i<31;$i++) $count[$i]=$i; ?>
    <div class="row">
        <?php echo $form->labelEx($model,'practice_reserv_count'); ?>
        <?php echo $form->dropDownList($model,'practice_reserv_count',$count);?>
        <?php echo $form->error($model,'practice_reserv_count'); ?>
    </div>
<?php } ?>
	<div class="row buttons">
		<?php echo TbHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->