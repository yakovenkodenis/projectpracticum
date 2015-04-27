<?php
/* @var $this UserController */
/* @var $model User */
$this->breadcrumbs=array(
	'Пользователи'=>array('admin'),
	'Создать нового',
);
?>

<h1>Создание пользователя</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>