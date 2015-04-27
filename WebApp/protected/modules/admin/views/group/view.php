<?php
/* @var $this GroupController */
/* @var $model Group */

$this->breadcrumbs=array(
	'Группы'=>array('admin'),
	$model->name,
);
?>

<h1>Группа <?php echo $model->name; ?></h1>
<?php echo CHtml::link('[редактировать]',array('group/update',"id" => $model->group_id)); ?>
<h4>Автошкола: <?php echo $model->autoschool->name; ?></h4>
<br />
<b><?php echo CHtml::encode($model->getAttributeLabel('practice_teacher')); ?>:</b>
<?php echo CHtml::encode($model->practiceTeacher->name); ?>
<br />

<b><?php echo CHtml::encode($model->getAttributeLabel('practice_meetpoint')); ?>:</b>
<?php echo CHtml::encode($model->practice_meetpoint); ?>
<br />

<b><?php echo CHtml::encode($model->getAttributeLabel('practice_start')); ?>:</b>
<?php echo CHtml::encode($model->practice_start); ?>
<br />

<b><?php echo CHtml::encode($model->getAttributeLabel('practice_days')); ?>:</b>
<?php echo CHtml::encode($model->practice_days); ?>
<br />

<b><?php echo CHtml::encode($model->getAttributeLabel('practice_reserv_count')); ?>:</b>
<?php echo CHtml::encode($model->practice_reserv_count); ?>
<br />

<br />
<h3>Студенты в группе:</h3>
<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id'=>'user-grid',
    'dataProvider'=>$students->searchByGroup($model->group_id),
    'type' => TbHtml::GRID_TYPE_STRIPED,
    'columns'=>array(
        array(
            'name'=>'Login',
            'value'=>'$data->student->login',
        ),
        array(
            'name'=>'Name',
            'value'=>'$data->student->name',
        ),
        array(
            'name'=>'Email',
            'value'=>'$data->student->email',
        ),

        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
            'updateButtonUrl'=>'Yii::app()->getUrlManager()->createUrl("admin/user/update", array("id" => $data->student_id))',
            'deleteButtonUrl'=>'Yii::app()->getUrlManager()->createUrl("admin/group/DeleteStudent", array("student_id" => $data->student_id,"group_id" =>$data->group_id))',
        ),
    ),
)); ?>
