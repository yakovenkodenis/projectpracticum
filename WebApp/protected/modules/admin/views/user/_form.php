<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>
<script>
    $(document).ready(function(){
        if($('#User_autoschool_id :selected').val()=="") {
            $('#User_group_id').prop("disabled", true);
        }
        $('#User_autoschool_id').change(function() {
            $('#User_group_id').prop("disabled", true);
            if($('#User_autoschool_id :selected').val()!="") {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $this->createUrl('/admin/theory/grouplist');?>',
                    data: 'autoschoolid=' + $(this).val(),
                    success: function (res) {
                        $('#User_group_id').html(res);
                        $('#User_group_id').prop("disabled", false);
                    }
                });
            }
        });
        $('#User_role').change(function(){
            role = $('#User_role');
            if(role.val()=="user" || role.val()=="administrator"){
                $('#school_row').css('display','none');
                $('#group_row').css('display','none');
            }
            else if(role.val()=="student"){
                $('#school_row').css('display','block');
                $('#group_row').css('display','block');
            }
            else{
                $('#school_row').css('display','block');
                $('#group_row').css('display','none');
            }
        });
    });
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note"><span class="required">*</span> - обязательные поля.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'login'); ?>
		<?php echo $form->textField($model,'login',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'login'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'role'); ?>
		<?php echo $form->dropDownList($model,'role',Yii::app()->user->getServicePremissionList()); ?>
		<?php echo $form->error($model,'role'); ?>
	</div>
    <?php if(Yii::app()->user->role == "administrator"): ?>
	<div class="row" id="school_row" <?php if($model->role=="administrator"||$model->role=="user") echo 'style="display:none;"';?> >
		<?php echo $form->labelEx($model,'autoschool_id'); ?>
		<?php echo $form->dropDownList($model,'autoschool_id',CHtml::listData(Autoschool::model()->findall(),'id','name'),array('empty' => '(Выберите школу)'));?>
		<?php echo $form->error($model,'autoschool_id'); ?>
	</div>
    <?php endif; ?>
    <div class="row" id="group_row" <?php if($model->role!="student") echo 'style="display:none;"';?> >
        <?php echo $form->labelEx($model,'group_id'); ?>
        <?php echo $form->dropDownList($model,'group_id',CHtml::listData(Group::model()->findAllByAttributes(array('autoschool_id'=>$model->autoschool_id)),'group_id','name'),array('empty' => '(Выберите группу)'));?>
        <?php echo $form->error($model,'group_id'); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'telephone'); ?>
		<?php echo $form->textField($model,'telephone',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'telephone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row buttons">
		<?php echo TbHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->