<?php
/* @var $this AutoschoolController */
/* @var $model Autoschool */

$this->widget('bootstrap.widgets.TbBreadcrumb', array(
    'links' => array(
    'Рассписание'
    ),
));
?>
<?php echo TbHtml::tabs(array(
    array('label' => 'Теоретические занятия', 'url' => '#', 'active' => true),
    array('label' => 'Практические', 'url' => 'practicelist',),
    array('label' => 'Бронирование', 'url' => 'practice',),
)); ?>
<h1>Расписание теоретических занятий</h1>
<table id="theoryTable">
    <tr>
        <th><?php echo Yii::app()->user->role == "student"?"Преподаватель":"Группа";?></th>
        <th>Комната</th>
        <th>Время начала</th>
        <th>Время окончания</th>
    </tr>
    <?php
    $today = new DateTime();
    $today->settime(0,0,0);
    foreach($theory as $item){
        $sdc = new DateTime($item->start_time);
        $sdc->settime(0,0,0);
        $addclass = "";
        if($today == $sdc){
            $addclass = "class='today'";
        }
        if($today > $sdc){
            $addclass = "class='passed'";
        }
        $sd = new DateTime($item->start_time);
        $ed = new DateTime($item->end_time);
        echo "<tr ".$addclass." >";
        echo "<td>".(Yii::app()->user->role == "student"?$item->teacher->name:$item->group->name)."</td>";
        echo "<td>".$item->room."</td>";
        echo "<td>".$sd->format("d.m.Y H.i.s")."</td>";
        echo "<td >".$ed->format("d.m.Y H.i.s")."</td>";
        echo "</tr>";
    }
?>
</table>