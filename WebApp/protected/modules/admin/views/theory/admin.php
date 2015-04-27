<?php
/* @var $this TheoryController */
/* @var $model Theory */

$this->breadcrumbs=array(
	'Теоретические занятия',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-form').toggle();
$('.search-form form').submit(function(){
	$('#theory-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Теоретические занятия</h1>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
    'autoschool'=>$autoscool,
    'group'=>$group
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'theory-grid',
	'dataProvider'=>$model->search(),
    'type' => TbHtml::GRID_TYPE_STRIPED,
	'filter'=>$model,
	'columns'=>array(
        array(
            'name'=>'teacher_id',
            'filter'=>$teachers,
            'value'=>'$data->teacher->name',
        ),
		'room',
        array(
            'name'=>'start_time',
            'value'=>'$data->start_time',
            'filter'=>false,
        ),
        array(
            'name'=>'end_time',
            'value'=>'$data->end_time',
            'filter'=>false,
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
        ),
	),
)); ?>
