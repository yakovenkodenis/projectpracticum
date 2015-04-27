<?php
class MobileController extends Controller
{
    public function actionLogin(){
        if(isset($_GET['user']) && isset($_GET['password'])){
            $user = User::model()->findByAttributes(array('login'=>$_GET['user']));
            if($user->password ==$_GET['password']){
                $res = "ok;".$user->role.';';
                $res .= $user->name.";";
                $group = Group::model()->findBySql("SELECT `group`.* from `group`,student_to_group
                where `group`.group_id=student_to_group.group_id AND student_to_group.student_id=".$user->id.";");
                $res .= $group->name.';';
                echo $res;
            }
            else{
                echo "LoginPasswordError";
            }
        }
        else{
                echo "ArgumentError";
        }
    }


    public function actionTheory(){
        if(isset($_GET['user']) && isset($_GET['password'])){
            $user = User::model()->findByAttributes(array('login'=>$_GET['user']));
            if($user->password ==$_GET['password']){
                if($user->role == "student"){
                    $stg = StudentToGroup::model()->findByAttributes(array('student_id'=>$user->id));
                    if($stg == null){
                        echo "nogroup;";
                    }
                    else {
                        $theory = Theory::model()->findAllBySql("SELECT theory.* from theory,student_to_group
                        WHERE theory.group_id=student_to_group.group_id AND student_to_group.student_id=" . $user->id . "
                        ORDER BY theory.start_time;");
                        foreach ($theory as $item) {
                            $group = Group::model()->findByPk($item->group_id);
                            $sd = new DateTime($item->start_time);
                            $ed = new DateTime($item->end_time);
                            echo $item->teacher->name . ";" . $group->name . ';' . $item->room . ";" . $sd->format("d.m.Y-H:i:s") . ";" . $ed->format("d.m.Y-H:i:s") . ";\n";
                        }
                    }
                }
                else if($user->role == "teacher"){
                    $theory = Theory::model()->findAllBySql("SELECT theory.* from theory
                    WHERE theory.teacher_id=".$user->id."
                    ORDER BY theory.start_time;");
                    foreach($theory as $item){
                        $sd = new DateTime($item->start_time);
                        $ed = new DateTime($item->end_time);
                        $group = Group::model()->findByPk($item->group_id);
                        echo $item->teacher->name.";".$group->name.';'.$item->room.";".$sd->format("d.m.Y-H:i:s").";".$ed->format("d.m.Y-H:i:s").";\n";
                    }
                }
                else{
                    echo "Role error";
                }
            }
            else{
                echo "LoginPasswordError";
            }
        }
        else{
            echo "ArgumentError";
        }
    }

    public function getDaysCount($start_day,$target_day,$autoschool_id){
        $sday = new DateTime($start_day->format('d.m.y'));
        $autoschoolsettings = PracticeSettings::model()->findByPk($autoschool_id);
        $days = MobileController::FormatWeekSettings($autoschoolsettings);
        $count=0;
        for($i=0;$i<$target_day;$i++){
            if($days[$sday->format("w")]==true){

            }
            else{
                $i--;
            }
            $count++;
            $sday->modify("+1 days");
        }
        return $count;
    }

    public function actionPractice(){
        if(isset($_GET['user']) && isset($_GET['password'])){
            $user = User::model()->findByAttributes(array('login'=>$_GET['user']));
            if($user->password ==$_GET['password']){
                if($user->role == "student"){
                    $group = Group::model()->findBySql("SELECT `group`.* from `group`,student_to_group
                    WHERE `group`.group_id = student_to_group.group_id AND student_to_group.student_id=".$user->id);
                    if($group == null){
                        echo "nogroup;";
                    }
                    else {
                        $practice = Practice::model()->findallbysql("SELECT practice.* from practice where practice.student_id=".$user->id .
                            " ORDER BY practice.day,practice.lesson;");
                        $start = new DateTime($group->practice_start);
                        foreach ($practice as $item) { // формируем правильное время
                            $item->group = $group;
                            $item->start_time = new DateTime($group->practice_start);
                            $item->start_time->setTime(0, 0, 0);
                            $item->start_time->modify("+".$this->getDaysCount($start,$item->day,$group->autoschool_id)." days");
                            $sadd = $this->getTime($group->autoschool_id, $item->lesson);
                            $item->start_time->modify("+" . $sadd . " seconds");
                            $item->end_time = new DateTime($item->start_time->format('d.m.Y H.i.s'));
                            $item->end_time->modify("+" . $this->getDuration($group->autoschool_id) . " seconds");
                            echo $item->group->practiceTeacher->name.";".$item->student->name.';'.$item->group->practice_meetpoint.";".$item->start_time->format("d.m.Y-H:i:s").";".$item->end_time->format("d.m.Y-H:i:s").";\n";
                        }
                    }
                }
                else if($user->role == "teacher"){
                    $practice = Practice::model()->findallbysql("SELECT practice.* from practice,student_to_group,`group` WHERE
                        practice.student_id=student_to_group.student_id AND student_to_group.group_id=`group`.group_id AND `group`.practice_teacher=".$user->id);
                  foreach($practice as $item){ // формируем правильное время
                        $item->group = Group::model()->findBySql("SELECT `group`.* from `group`,student_to_group
                       WHERE `group`.group_id = student_to_group.group_id AND student_to_group.student_id=".$item->student_id);
                        $start = new DateTime($item->group->practice_start);
                        $item->start_time = new DateTime($item->group->practice_start);
                        $item->start_time->setTime(0,0,0);
                        $item->start_time->modify("+".$this->getDaysCount($start,$item->day,$item->group->autoschool_id)." days");
                        $sadd = $this->getTime($item->group->autoschool_id,$item->lesson);
                        $item->start_time->modify("+".$sadd." seconds");
                        $item->end_time = new DateTime($item->start_time->format('d.m.Y H.i.s'));
                        $item->end_time->modify("+".$this->getDuration($item->group->autoschool_id)." seconds");
                  }
                    usort($practice, function($a, $b){ // сортируем по дате
                        if ($a->start_time == $b->start_time) {
                            return 0;
                        }
                        return ($a->start_time < $b->start_time) ? -1 : 1;
                    });
                    foreach($practice as $item){

                        echo $item->group->practiceTeacher->name.";".$item->student->name.';'.$item->group->practice_meetpoint.";".$item->start_time->format("d.m.Y-H:i:s").";".$item->end_time->format("d.m.Y-H:i:s").";\n";
                    }
                }
                else{
                    echo "Role error";
                }
            }
            else{
                echo "LoginPasswordError";
            }
        }
        else{
            echo "ArgumentError";
        }
    }
    private function getDuration($autoschool_id){
        $settings = PracticeSettings::model()->findByPK($autoschool_id);
        $sd = new DateTime($settings->duration);
        return $sd->format('H')*3600+$sd->format('i')*60+$sd->format('s');
    }

    private function getTime($autoschool_id,$time_id){
        $settings = PracticeSettings::model()->findByPK($autoschool_id);
        if($time_id==0){
            $sd = new DateTime($settings->practice1);
        }
        if($time_id==1){
            $sd = new DateTime($settings->practice2);
        }
        if($time_id==2){
            $sd =  new DateTime($settings->practice3);
        }
        if($time_id==3){
            $sd =  new DateTime($settings->practice4);
        }
        if($time_id==4){
            $sd =  new DateTime($settings->practice5);
        }
        if($time_id==5){
            $sd = new  DateTime($settings->practice6);
        }
        if($time_id==6){
            $sd = new    DateTime($settings->practice7);
        }
        return $sd->format('H')*3600+$sd->format('i')*60+$sd->format('s');
    }

    private static function FormatWeekSettings($PracticeSettings){
        $res = array();
        $res[0] = $PracticeSettings->sunday==1;
        $res[1] = $PracticeSettings->monday==1;
        $res[2] = $PracticeSettings->tuesday==1;
        $res[3] = $PracticeSettings->wednesday==1;
        $res[4] = $PracticeSettings->thursday==1;
        $res[5] = $PracticeSettings->friday==1;
        $res[6] = $PracticeSettings->saturday==1;
        for($i=0;$i<7;$i++){
            if($res[$i]==true){
                return $res;
            }
        }
        return null;
    }
}