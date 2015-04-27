<?php
/* @var $this GroupController */
/* @var $model Group */

$this->breadcrumbs=array(
	'Практические занятия'
);
?>
<?php if(Yii::app()->user->role == "administrator"): ?>
<script>
    $(document).ready(function(){
        if($('#autoSchoolAjaxDropDown :selected').val()!=""){
            $.ajax({
                type: 'POST',
                url: '<?php echo $this->createUrl('/admin/theory/grouplist');?>',
                data: 'autoschoolid=' + $('#autoSchoolAjaxDropDown :selected').val(),
                success: function (res) {
                    $('#groupDropDown').html(res);
                    $('#groupDropDown').prop("disabled", false);
                    $('#groupDropDown').val(<?php echo $_POST['groupDropDown']; ?>);
                }
            });
        }
        $('#groupDropDown').prop("disabled", true);
        $('#autoSchoolAjaxDropDown').change(function() {
            $('#groupDropDown').prop("disabled", true);
            if($('#autoSchoolAjaxDropDown :selected').val()!="") {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo $this->createUrl('/admin/theory/grouplist');?>',
                    data: 'autoschoolid=' + $(this).val(),
                    success: function (res) {
                        $('#groupDropDown').html(res);
                        $('#groupDropDown').prop("disabled", false);
                    }
                });
            }
        });
    });
</script>
<?php endif; ?>
<h1>Пракитческие занятия</h1>
<div class="search-form">
<div class="wide form">

    <?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm')?>
    <?php if(Yii::app()->user->role == "administrator"): ?>
    <div class="row" id="school">
        <?php echo TbHtml::label("Школа",autoSchoolAjaxDropDown,array("style"=>"margin-top:5px")); ?>
        <?php echo TbHtml::dropDownList("autoSchoolAjaxDropDown",$_POST['autoSchoolAjaxDropDown'],CHtml::listData(Autoschool::model()->findall(),'id','name'),array('empty' => '(Выберите школу)'));?>
    </div>
    <?php endif; ?>

    <div class="row">
        <?php echo TbHtml::label("Группа",groupDropDown,array("style"=>"margin-top:5px")); ?>
        <?php if(Yii::app()->user->role == "administrator"): ?>
        <?php echo TbHtml::dropDownList('groupDropDown',$_POST['groupDropDown'],array('empty' => '(Выберите группу)'),array('id'=>'groupDropDown'));?>
        <?php else: ?>
        <?php echo TbHtml::dropDownList('groupDropDown',$_POST['groupDropDown'],CHtml::listData($groups,'group_id','name'),array('empty' => '(Выберите школу)'),array('id'=>'groupDropDown'));?>
        <?php endif; ?>
    </div>

    <div class="row buttons" id="searchbutton">
        <?php echo TbHtml::submitButton('Поиск'); ?>
    </div>
    <?php $this->endWidget(); ?>
</div>
</div>
    <?php if(isset($group)){ ?>
<h2>Практические занятия для группы <?php echo $group->name;?></h2>
    <?php } ?>
<?php $this->renderPartial("_form",array(
    'dates'=>$dates,
    'starttime'=>$starttime,
    'endtime'=>$endtime,
    'practice'=>$practice
))?>
