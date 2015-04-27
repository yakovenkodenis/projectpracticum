<?php
/* @var $this GroupController */
/* @var $data Group */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('autoschool_id')); ?>:</b>
	<?php echo CHtml::encode($data->autoschool->name); ?>
	<br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('practice_teacher')); ?>:</b>
    <?php echo CHtml::encode($data->practice_teacher->name); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('practice_start')); ?>:</b>
    <?php echo CHtml::encode($data->practice_start); ?>
    <br />

    <b><?php echo CHtml::encode($data->getAttributeLabel('practice_days')); ?>:</b>
    <?php echo CHtml::encode($data->practice_days); ?>
    <br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->group_id)); ?>
	<br />


</div>