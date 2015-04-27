<?php
/* @var $this TheoryController */
/* @var $model Theory */

$this->breadcrumbs=array(
	'Теория'=>array('admin'),
	'Создать',
);
?>

<h1>Создать теоретическое занятие</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'teachers'=>$teachers,'groups'=>$groups)); ?>