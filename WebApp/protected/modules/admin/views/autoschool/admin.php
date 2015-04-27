<?php
/* @var $this AutoschoolController */
/* @var $model Autoschool */

$this->breadcrumbs=array(
	'Автошколы',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#autoschool-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Настройка автошкол</h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'autoschool-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'htmlOptions' => array('style'=>'width:1000px;'),
    'cssFile' => false,
    'type' => TbHtml::GRID_TYPE_STRIPED,
	'columns'=>array(
		'name',
		'contacts',
		array(
			'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
		),
	),
)); ?>
