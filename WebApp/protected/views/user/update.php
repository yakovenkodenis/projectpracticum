<?php
/* @var $this AutoschoolController */
/* @var $model Autoschool */

$this->widget('bootstrap.widgets.TbBreadcrumb', array(
    'links' => array(
	'Профиль' =>"index",
    'Редактировать'
    ),
));
?>

<h1>Просмотр профиля</h1>
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
<table id="userEditProfileTable">
<tr>
    <td><?php echo $form->labelEx($model,'login'); ?></td>
    <td><?php echo $model->login; ?></td>
</tr>

    <tr>
        <td><?php echo $form->labelEx($model,'password'); ?></td>
        <td><?php echo $form->passwordField($model,'password',array('size'=>40,'maxlength'=>40)); ?></td>
    </tr>

    <?php if(Yii::app()->user->role == "student"): ?>
    <tr>
        <td><?php echo $form->labelEx($model,'group_id'); ?></td>
        <td><?php echo $form->dropDownList($model,'group_id',CHtml::listData(Group::model()->findAllByAttributes(array('autoschool_id'=>$model->autoschool_id)),'group_id','name'),array('empty' => '(Выберите группу)'));?></td>
    </tr>
    <?php endif; ?>
    <tr>
        <td><?php echo $form->labelEx($model,'email'); ?></td>
        <td><?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?></td>
    </tr>

    <tr>
        <td><?php echo $form->labelEx($model,'name'); ?></td>
        <td><?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?></td>
    </tr>

    <tr>
        <td><?php echo $form->labelEx($model,'telephone'); ?></td>
        <td><?php echo $form->textField($model,'telephone',array('size'=>60,'maxlength'=>255)); ?></td>
    </tr>

    <tr>
        <td><?php echo $form->labelEx($model,'address'); ?></td>
        <td><?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>255)); ?></td>
    </tr>
    <tr>
        <td class="row buttons">
            <?php echo TbHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
        </td>
    </tr>
</table>
<?php $this->endWidget(); ?>


