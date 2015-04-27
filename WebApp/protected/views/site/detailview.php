<?php
/* @var $this AutoschoolController */
/* @var $model Autoschool */

$this->widget('bootstrap.widgets.TbBreadcrumb', array(
    'links' => array(
	'Автошколы'=>array('autoschool'),
    ),
));
?>

<h1><?php echo $model->name; ?></h1>

<?php echo TbHtml::tabbableTabs(array(
    array('label' => 'Описание', 'active' => true, 'content' => $model->info),
    array('label' => 'Запись и Цены', 'content' => $model->price),
    array('label' => 'Контакты', 'content' => $model->contacts),
    array('label' => 'Отзывы', 'content' => '<p>тут будут отзывы.</p>'),
)); ?>
