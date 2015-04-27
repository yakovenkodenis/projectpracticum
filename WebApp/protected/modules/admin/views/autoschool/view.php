<?php
/* @var $this AutoschoolController */
/* @var $model Autoschool */

$this->breadcrumbs=array(
	'Autoschools'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Autoschool', 'url'=>array('index')),
	array('label'=>'Create Autoschool', 'url'=>array('create')),
	array('label'=>'Update Autoschool', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Autoschool', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Autoschool', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->name; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'name',
		'contacts',
		'info',
        'price',
	),
)); ?>
