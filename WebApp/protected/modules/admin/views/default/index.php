<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	'Панель управления',
);
?>
<?php if(Yii::app()->user->hasFlash('result')): ?>

<div class="flash-success">
    <?php echo Yii::app()->user->getFlash('result'); ?>
</div>
<?php endif; ?>
<h1>Добро пожаловать в панель управления</h1>
<p>Сдесь вы можете полностью настроить работу ваших школ, а также изменить настройки сайта.</p>

