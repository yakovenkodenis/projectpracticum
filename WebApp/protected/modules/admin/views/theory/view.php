<?php
/* @var $this TheoryController */
/* @var $model Theory */

$this->breadcrumbs=array(
	'Теория'=>array('admin'),
	$model->theory_id,
);

$this->menu=array(
	array('label'=>'List Theory', 'url'=>array('index')),
	array('label'=>'Create Theory', 'url'=>array('create')),
	array('label'=>'Update Theory', 'url'=>array('update', 'id'=>$model->theory_id)),
	array('label'=>'Delete Theory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->theory_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Theory', 'url'=>array('admin')),
);
?>

<h1>View Theory #<?php echo $model->theory_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'theory_id',
		'teacher_id',
		'group_id',
		'room',
		'start_time',
		'end_time',
	),
)); ?>
