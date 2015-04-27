<?php
/* @var $this GroupController */
/* @var $model Group */
/* @var $form CActiveForm */
?>
<script>
    $(document).ready(function () {
        $(".reserveRemoveButton").click(return confirm("Вы уверены,
        что хотите покинуть сайт?");
    })
</script>
<?php
if(count($dates)>0) { // есть начало практики и даты установленны
    ?>
<table class="practicetable">
    <?php
    if(count($dates)>0) { // есть начало практики и даты установленны
        $today = new DateTime();
        $today->setTime(23,59,59);
        for ($i = -1; $i < count($starttime); $i++) {
            echo "<tr>";
            for ($j = -1; $j < count($dates); $j++) {
                if ($i == -1) { // если это первый ряд то рисуем заголовки
                    if (isset($dates[$j])) { // если есть дата то выводим её
                        echo "<th>" . $dates[$j]->format("d.m.y") . "</th>";
                    } else { //    иначе выводим пустой заголовок
                        echo "<th></th>";
                    }
                } else if ($j == -1) { // если первый столбец то рисуем время
                    echo "<td>" . $starttime[$i] . "<br />" . $endtime[$i] . "</td>";
                } else {
                    if ($practice[$i][$j] != null) {
                        if($practice[$i][$j]->student->id == Yii::app()->user->id){
                            if($dates[$j]>$today){
                                echo "<td class='practiceSelfReserved'>" . $practice[$i][$j]->student->name .
                                    "<br/><a class='reserveRemoveButton' onclick='return confirm(\"Вы дествительно хотите отменить запись?\")'
                                    href=".$this->createUrl('deletebrone',array('lesson'=>$i,'day'=>$j)).">Отмена</a>".
                                    "</td>";
                            }
                            else{
                                echo "<td class='practiceSelfReserved'>" . $practice[$i][$j]->student->name."</td>";
                            }
                        }
                        else {
                            echo "<td class='practiceReserved'>" . $practice[$i][$j]->student->name . "</td>";
                        }
                    } else {

                        if(Yii::app()->user->role == "student" && $dates[$j]>$today && $limit>0){
                            echo '<td><a class="reserveButton" href="'.$this->createUrl('brone',array('lesson'=>$i,'day'=>$j)).'">Запись</a></td>';
                        }else {
                            echo "<td>Свободно</td>";
                        }
                    }
                }
            }
            echo "</tr>";
        }
    ?>
</table>
<?php }

    }
else{ ?>
    <h3>Для вашей группы практических занятий нет</h3>
<?php }?>