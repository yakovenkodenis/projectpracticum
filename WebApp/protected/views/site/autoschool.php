<?php
/* @var $this AutoschoolController */
/* @var $dataProvider CActiveDataProvider */

$this->widget('bootstrap.widgets.TbBreadcrumb', array(
    'links' => array(
	'Автошколы',
    ),
));
?>
<?php $schoollist = array();
    foreach($autoschool as $item){
        if($school!=null &&$school->id == $item->id){
            array_push($schoollist, array('label' => $item->name, 'url' => $this->createUrl('autoschool', array('id' => $item->id)), 'active'=>'true'));
        }
        else {
            array_push($schoollist, array('label' => $item->name, 'url' => $this->createUrl('autoschool', array('id' => $item->id))));
        }
    }
?>
<h1>Автошколы</h1>
<table>
    <tr>
<td style="width: 230px;vertical-align: top;">
<div class="well" style="float:left;min-width: 220px; padding: 8px 0; margin:0 10px;">
    <?php echo TbHtml::navList($schoollist); ?>
</div>
</td>
        <td>
<?php if($school!=null){
    echo TbHtml::tabbableTabs(array(
        array('label' => 'Описание', 'active' => true, 'content' => $school->info),
        array('label' => 'Цены', 'content' => $school->price),
        array('label' => 'Контакты', 'content' => $school->contacts),
    ));
     }?>
        </td> </tr>
</table>
