<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Пользователи',
);
?>

<h1>Настройка пользователей</h1>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'type' => TbHtml::GRID_TYPE_STRIPED,
    'htmlOptions' => array('style'=>'width:1000px;'),
	'columns'=>array(
		'login',
        array(
            'name'=>'role',
            'filter'=>Yii::app()->user->getServicePremissionList(),
        ),
        'name',
		'email',
		/*
		'f_name',
		'l_name',
		'telephone',
		'address',
		*/
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
        ),
	),
)); ?>
