<?php
/* @var $this GroupController */
/* @var $model Group */
/* @var $form CActiveForm */
?>
<?php
if(count($dates)>0) { // есть начало практики и даты установленны
    ?>
<table class="practicetable">
    <?php
    if(count($dates)>0) { // есть начало практики и даты установленны
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
                        echo "<td class='practiceReserved'>" . $practice[$i][$j]->student->name . "</td>";
                    } else {
                        echo "<td>Свободно</td>";
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