<?php
/* @var $this AutoschoolController */
/* @var $model Autoschool */
/* @var $form CActiveForm */
?>

<script type="text/javascript">
    $(document).ready(function() {
        for (i = 1; i < 8; i++) {
            $('#PracticeSettings_practice'+i).timepicker({minuteStep: 5,showMeridian: false,defaultTime: (6+2*i)+':00:00',showSeconds: true});
        }
        $('#PracticeSettings_duration').timepicker({minuteStep: 5,showMeridian: false,defaultTime: '01:00:00',showSeconds: true});
    });
</script>

<?php $this->widget('bootstrap.widgets.TbNav', array(
    'type' => TbHtml::NAV_TYPE_TABS,
    'items' => array(
        array('label' => 'Общая информация', 'url' => Yii::app()->createUrl('/admin/autoschool/update',array('id'=>$model->autoschool_id)), ),
        array('label' => 'Практика', 'url' => '#','active' => true),
    ),
)); ?>
<div class="form" id="practiceSettings">

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'practice-settings-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation'=>false,
    )); ?>

<p class="note"><span class="required">*</span> - обязательные поля.</p>

<?php echo $form->errorSummary($model); ?>
    <table id="practiceSettings">
        <tr>
            <th colspan="2">Дни недели</th>
            <th colspan="2">Время начала занятий</th>
        </tr>
    <tr>
        <td><?php echo $form->checkBox($model,'monday'); ?></td>
        <td><?php echo $form->labelEx($model,'monday'); ?></td>
        <td><?php echo $form->labelEx($model,'practice1'); ?></td>
        <td><?php echo $form->textField($model,'practice1',array('data-format'=>'hh:mm:ss')); ?></td>
    </tr>
        <tr>
            <td><?php echo $form->checkBox($model,'tuesday'); ?></td>
            <td><?php echo $form->labelEx($model,'tuesday'); ?></td>
            <td><?php echo $form->labelEx($model,'practice2'); ?></td>
            <td> <?php echo $form->textField($model,'practice2'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->checkBox($model,'wednesday'); ?></td>
            <td><?php echo $form->labelEx($model,'wednesday'); ?></td>
            <td><?php echo $form->labelEx($model,'practice3'); ?></td>
            <td> <?php echo $form->textField($model,'practice3'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->checkBox($model,'thursday'); ?></td>
            <td><?php echo $form->labelEx($model,'thursday'); ?></td>
            <td><?php echo $form->labelEx($model,'practice4'); ?></td>
            <td> <?php echo $form->textField($model,'practice4'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->checkBox($model,'friday'); ?></td>
            <td><?php echo $form->labelEx($model,'friday'); ?></td>
            <td><?php echo $form->labelEx($model,'practice5'); ?></td>
            <td> <?php echo $form->textField($model,'practice5'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->checkBox($model,'saturday'); ?></td>
            <td><?php echo $form->labelEx($model,'saturday'); ?></td>
            <td><?php echo $form->labelEx($model,'practice6'); ?></td>
            <td> <?php echo $form->textField($model,'practice6'); ?></td>
        </tr>
        <tr>
            <td><?php echo $form->checkBox($model,'sunday'); ?></td>
            <td><?php echo $form->labelEx($model,'sunday'); ?></td>
            <td><?php echo $form->labelEx($model,'practice7'); ?></td>
            <td> <?php echo $form->textField($model,'practice7'); ?></td>
        </tr>

    <div class="row" style="padding: 10px;">
        <?php echo $form->labelEx($model,'duration'); ?>
        <?php echo $form->textField($model,'duration'); ?>
        <?php echo $form->error($model,'duration'); ?>
    </div>
    </table>

    <p class="note"><span class="required">*</span> для того чтобы удалить урок удалите его время.</p>

    <div class="row buttons">
        <?php echo TbHtml::submitButton('Сохранить'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->