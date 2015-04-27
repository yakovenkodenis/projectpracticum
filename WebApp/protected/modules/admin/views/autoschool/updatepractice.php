<?php
/* @var $this AutoschoolController */
/* @var $model Autoschool */
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/bootstrap-datetimepicker.min.js');
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/bootstrap-datetimepicker.min.css');

$this->breadcrumbs=array(
	'Автошколы'=>array('admin'),
	'Редактирование',
);

?>

<h1>Редактирование автошколы</h1>
<h2><?php echo $model->autoschool->name; ?></h2>

<?php $this->renderPartial('_formpractice', array('model'=>$model)); ?>