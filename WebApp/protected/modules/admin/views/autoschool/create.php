<?php
/* @var $this AutoschoolController */
/* @var $model Autoschool */

$this->breadcrumbs=array(
	'Автошколы'=>array('admin'),
	'Создать новую',
);
?>

<h1>Создание автошколы</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>