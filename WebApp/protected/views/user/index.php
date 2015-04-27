<?php
/* @var $this AutoschoolController */
/* @var $model Autoschool */

$this->widget('bootstrap.widgets.TbBreadcrumb', array(
    'links' => array(
	'Профиль',
    ),
));
?>

<h1>Просмотр профиля</h1>
<?php echo CHtml::link('[редактировать профиль]',array('update'));?>
<?php if(Yii::app()->user->role=="user" || Yii::app()->user->role=="student" || Yii::app()->user->role=="teacher"){ ?>
<br /><?php echo CHtml::link('[поменять код автошколы]',array('code'));?>
<?php }?>
<br />
<?php if(Yii::app()->user->role == "student" && $model->group_id == "" ){
echo TbHtml::alert(TbHtml::ALERT_COLOR_WARNING, 'Внимание!<br />Для получения рассписания укажите группу в настройках!');
}?>
<br />
<table class="items table table-condensed" id="userProfileTable">
    <tr>
        <td><?php echo CHtml::activeLabelEx($model,'login'); ?></td>
        <td><?php echo $model->login; ?></td>
    </tr>
    <tr>
        <td><?php echo CHtml::activeLabelEx($model,'name'); ?></td>
        <td><?php echo $model->name; ?></td>
    </tr>
    <tr>
        <td><?php echo CHtml::activeLabelEx($model,'role'); ?></td>
        <td><?php echo $model->role; ?></td>
    </tr>
    <?php if(Yii::app()->user->role != "user" && Yii::app()->user->role != "administrator"): ?>
    <tr>
        <td><?php echo CHtml::activeLabelEx($model,'autoschool_id'); ?></td>
        <td><?php echo $model->autoschool->name; ?></td>
    </tr>
    <?php endif; ?>

    <?php if(Yii::app()->user->role == "student"): ?>
    <tr>
        <td><?php echo CHtml::activeLabelEx($model,'group_id'); ?></td>
        <td><?php echo $group->name; ?></td>
    </tr>
    <?php endif; ?>
    <tr>
        <td><?php echo CHtml::activeLabelEx($model,'email'); ?></td>
        <td><?php echo $model->email; ?></td>
    </tr>
    <tr>
        <td><?php echo CHtml::activeLabelEx($model,'telephone'); ?></td>
        <td><?php echo $model->telephone; ?></td>
    </tr>
    <tr>
        <td><?php echo CHtml::activeLabelEx($model,'address'); ?></td>
        <td><?php echo $model->address; ?></td>
    </tr>
</table>


