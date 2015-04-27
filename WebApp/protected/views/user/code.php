<?php
/* @var $this AutoschoolController */
/* @var $model Autoschool */

$this->widget('bootstrap.widgets.TbBreadcrumb', array(
    'links' => array(
	'Профиль',
    ),
));
?>

<h1>Код автошколы</h1>

<h4>Укажите код автошколы, который вам выдали</h4>

<?php if(Yii::app()->user->hasFlash('result')): ?>
    <div class="flash-error">
        <?php echo Yii::app()->user->getFlash('result'); ?>
    </div>
<?php endif; ?>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'login-form',
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
    'action'=>$this->createUrl('user/code'),
)); ?>
<div class="row" style="margin:0px;">
    Код:
</div>
<div class="row" style="margin:0px;">
    <input type="text" name="code"/>
</div>

<div class="row buttons" style="margin:0px;">
    <?php echo TbHtml::submitButton('Сохранить'); ?>
</div>

<?php $this->endWidget(); ?>

