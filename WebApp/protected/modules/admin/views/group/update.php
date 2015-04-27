<?php
/* @var $this GroupController */
/* @var $model Group */

$this->breadcrumbs=array(
	'Группы'=>array('admin'),
	$model->name=>array('view','id'=>$model->group_id),
	'Редактирование',
);
?>

<h1>Редактирование группы <?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>