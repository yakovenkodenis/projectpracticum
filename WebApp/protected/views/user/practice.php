<?php
/* @var $this GroupController */
/* @var $model Group */

$this->widget('bootstrap.widgets.TbBreadcrumb', array(
    'links' => array(
        'Рассписание'
    ),
));
?>
<?php echo TbHtml::tabs(array(
    array('label' => 'Теоретические занятия', 'url' => 'theory', ),
    array('label' => 'Практические', 'url' => 'practicelist',),
    array('label' => 'Бронирование', 'url' => '#','active' => true),
)); ?>
<h1>Практические занятия</h1>
<?php if(Yii::app()->user->role == "teacher"): ?>
<div class="search-form">
<div class="wide form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm')?>

    <div class="row">
        <?php echo TbHtml::label("Группа",groupDropDown,array("style"=>"margin-top:5px")); ?>
        <?php if(Yii::app()->user->role == "administrator"): ?>
        <?php echo TbHtml::dropDownList('groupDropDown',$_POST['groupDropDown'],array('empty' => '(Выберите группу)'),array('id'=>'groupDropDown'));?>
        <?php else: ?>
        <?php echo TbHtml::dropDownList('groupDropDown',$_POST['groupDropDown'],CHtml::listData($groups,'group_id','name'),array('empty' => '(Выберите группу)'),array('id'=>'groupDropDown'));?>
        <?php endif; ?>
    </div>

    <div class="row buttons" id="searchbutton">
        <?php echo TbHtml::submitButton('Поиск'); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>
</div>
<?php endif; ?>

    <?php if(isset($group)){ ?>
<h2>Практические занятия для группы <?php echo $group->name;?></h2>
        <?php if(Yii::app()->user->role == "student"){
            echo "<h4>Можно забронировать еще ".$limit." занятия.</h4>";
        }?>
    <?php } ?>
<?php $this->renderPartial("_form",array(
    'dates'=>$dates,
    'starttime'=>$starttime,
    'endtime'=>$endtime,
    'practice'=>$practice,
    'limit'=>$limit,
))?>
