<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Пользователи'=>array('admin'),
	'Редактирование',
);
?>

<h1>Редактирование пользователя <b><?php echo $model->login; ?></b></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>