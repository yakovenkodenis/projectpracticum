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
    array('label' => 'Теоретические занятия', 'url' => 'theory',),
    array('label' => 'Практические', 'url' => 'practicelist', 'active' => true),
    array('label' => 'Бронирование', 'url' => 'practice',),
)); ?>
<h1>Расписание практических занятий</h1>
<table id="theoryTable">
    <tr>
        <th><?php echo Yii::app()->user->role == "student"?"Преподаватель":"Студент";?></th>
        <th>Место сбора</th>
        <th>Время начала</th>
        <th>Время окончания</th>
    </tr>
    <?php
    $today = new DateTime();
    $today->settime(0,0,0);
    foreach($model as $item){
        $addclass = "";
        $sdc = new DateTime($item->start_time->format("d.m.Y"));
        $sdc->settime(0,0,0);
        if($today == $sdc){
            $addclass = "class='today'";
        }
        if($today > $sdc){
            $addclass = "class='passed'";
        }
        echo "<tr ".$addclass." >";
        echo "<td>".(Yii::app()->user->role == "student"?$item->group->practiceTeacher->name:$item->student->name)."</td>";
        echo "<td>".$item->group->practice_meetpoint."</td>";
        echo "<td>".$item->start_time->format("d.m.Y H.i.s")."</td>";
        echo "<td >".$item->end_time->format("d.m.Y H.i.s")."</td>";
        echo "</tr>";
    }
?>
</table>