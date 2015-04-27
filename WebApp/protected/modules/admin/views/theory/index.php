<?php
/* @var $this TheoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Теория',
);
?>

<h1>Theories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
