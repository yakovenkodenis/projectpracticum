<?php
/* @var $this UserController */
/* @var $model User */
$this->widget('bootstrap.widgets.TbBreadcrumb', array(
    'links' => array(
	'Регистрация',
    ),
));
?>
<?php if(Yii::app()->user->hasFlash('registration')): ?>

    <div class="flash-success">
        <?php echo Yii::app()->user->getFlash('registration'); ?>
        <br />
        <?php echo CHtml::link("Войти на сайт",array('site/login')); ?>
    </div>

<?php else: ?>
<h1>Создание пользователя</h1>

<?php $this->renderPartial('_registrationform', array('model'=>$model)); ?>

<?php endif; ?>