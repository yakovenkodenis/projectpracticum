<?php
/* @var $this TheoryController */
/* @var $model Theory */
/* @var $form CActiveForm */
?>
<script>
    $(document).ready(function(){
        <?php if(Yii::app()->user->role == "administrator"): ?>
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
        $('#groupDropDown').change(function(){
            $('#yw0').submit();
        });
        <?php else: ?>
        $('#Theory_group_id').change(function(){
            $('#yw0').submit();
        });
        <?php endif; ?>
        $('#yw0 #searchbutton').css("display","none");
    });
</script>
<div class="wide form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
    <?php if(Yii::app()->user->role == "administrator"): ?>
    <div class="row" id="school">
        <?php echo TbHtml::label("Школа",autoSchoolAjaxDropDown,array("style"=>"margin-top:5px")); ?>
        <?php echo CHtml::dropDownList("autoSchoolAjaxDropDown","empty",CHtml::listData(Autoschool::model()->findall(),'id','name'),array('empty' => '(Выберите школу)'));?>
    </div>
    <?php endif; ?>
	<div class="row">

		<?php echo $form->label($model,'group_id'); ?>
        <?php if(Yii::app()->user->role == "moderator"): ?>
        <?php   echo $form->dropDownList($model,'group_id',CHtml::listData($group,'group_id','name'),array('empty' => '(Выберите группу)'));?>
        <?php else: ?>
        <?php echo $form->dropDownList($model,'group_id',array('empty' => '(Выберите группу)'),array('id'=>'groupDropDown'));?>
        <?php endif; ?>
	</div>

	<div class="row buttons" id="searchbutton">
		<?php echo CHtml::submitButton('Поиск'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->