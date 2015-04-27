<?php
/* @var $this AutoschoolController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Autoschools',
);

$this->menu=array(
	array('label'=>'Create Autoschool', 'url'=>array('create')),
	array('label'=>'Manage Autoschool', 'url'=>array('admin')),
);
?>

<h1>Автошколы</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
