<?php
/* @var $this GroupController */
/* @var $model Group */

$this->breadcrumbs=array(
	'Группы'=>array('admin'),
	'Создать',
);
?>

<h1>Создание группы</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'autoschool'=>$autoschool)); ?>