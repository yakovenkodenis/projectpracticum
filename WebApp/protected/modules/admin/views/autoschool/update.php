<?php
/* @var $this AutoschoolController */
/* @var $model Autoschool */

$this->breadcrumbs=array(
	'Автошколы'=>array('admin'),
	'Редактирование',
);

?>

<h1>Редактирование автошколы</h1>
<h2><?php echo $model->name; ?></h2>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>