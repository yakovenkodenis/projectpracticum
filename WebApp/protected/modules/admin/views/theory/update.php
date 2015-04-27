<?php
/* @var $this TheoryController */
/* @var $model Theory */

$this->breadcrumbs=array(
	'Теория'=>array('index'),
	'Редактировать',
);
?>

<h1>Редактировать теоретическое занятие <?php echo $model->theory_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'teachers'=>$teachers,'groups'=>$groups)); ?>