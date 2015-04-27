<?php
/* @var $this GroupController */
/* @var $model Group */
/* @var $form CActiveForm */
?>
<script>
    $(document).ready(function(){
        $('#Group_autoschool_id').change(function(){
            $('#yw0').submit();
        });
    });
</script>
<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'autoschool_id'); ?>
        <?php echo $form->dropDownList($model,'autoschool_id',CHtml::listData(Autoschool::model()->findall(),'id','name'),array('empty' => '(Выберите школу)'));?>

    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->