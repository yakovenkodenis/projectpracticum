<?php

class UserController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
    public function beforeAction($action)
    {
        if (parent::beforeAction($action))
        {
            if (Yii::app()->user->isGuest)
            {
                Yii::app()->request->redirect(Yii::app()->homeUrl);
            }
            return true;
        }
        else
            return false;
    }

    public function actionCode(){
        if(isset($_POST['code'])){
            $result = $this->GetAutschoolByCode($_POST['code']);
            $model = YII::app()->user->getUserModel();
            if($result!=null){
                if($result->studentCode == $_POST['code']){
                    $model->autoschool_id = $result->id;
                    $model->role = 'student';
                }
                else if($result->teacherCode == $_POST['code']){
                    $model->autoschool_id = $result->id;
                    $model->role = 'teacher';
                }
                else{
                    $model->role = 'user';
                }
                if($model->save()){
                    $this->redirect('index');
                }
            }
            else{
                Yii::app()->user->setFlash('result','Код недействителен');
                $this->render('code');
            }
        }
        $this->render('code');
    }

    public function GetAutschoolByCode($code){
        $model = Autoschool::model()->findByAttributes(array('studentCode'=>$code));
        if(isset($model)){
            return $model;
        }
        $model = Autoschool::model()->findByAttributes(array('teacherCode'=>$code));
        if(isset($model)){
            return $model;
        }
        else
            return null;
    }

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
        if(Yii::app()->user->isGuest){
            $this->redirect('login');
        }
        else{
            $group = null;
            $model = $this->loadModel(Yii::app()->user->id);
            if($model->role=="student"){
                $group = Group::model()->findBySql("SELECT `group`.* from `group`,`student_to_group` WHERE
                          `group`.group_id=student_to_group.group_id AND student_to_group.student_id=".$model->id);
            }
            $this->render('index',
                array('model'=>$model,
                'group'=>$group));
        }
	}

    public function actionUpdate(){
        if(Yii::app()->user->isGuest){
            $this->redirect('login');
        }
        else{
            $group = null;
            $model = $this->loadModel(Yii::app()->user->id);
            if(isset($_POST['User']))
            {
                $model->attributes=$_POST['User'];
                if($model->save())
                    $this->redirect(array('index'));
            }
            if($model->role=="student"){
                $group = Group::model()->findBySql("SELECT `group`.* from `group`,`student_to_group` WHERE
                          `group`.group_id=student_to_group.group_id AND student_to_group.student_id=".$model->id);
            }
            $this->render('update',
                array('model'=>$model,
                    'group'=>$group));
        }
    }

    public function actionTheory(){
        if(Yii::app()->user->isGuest){
            $this->redirect('login');
        }
        else{
            $user = Yii::app()->user->getUserModel();
            if($user->role == "student"){
                $stg = StudentToGroup::model()->findByAttributes(array('student_id'=>$user->id));
                if($stg == null){
                    $this->redirect('index');
                }
                else {
                    $theory = Theory::model()->findAllBySql("SELECT theory.* from theory,student_to_group
                        WHERE theory.group_id=student_to_group.group_id AND student_to_group.student_id=" . $user->id . "
                        ORDER BY theory.start_time;");
                    $this->render("theory",array('theory'=>$theory));
                }
            }
            else if($user->role == "teacher"){
                $theory = Theory::model()->findAllBySql("SELECT theory.* from theory
                    WHERE theory.teacher_id=".$user->id."
                    ORDER BY theory.start_time;");
                $this->render("theory",array('theory'=>$theory));
            }
        }
    }

    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function loadModel($id)
    {
        $model=User::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        $group = StudentToGroup::model()->findByAttributes(array('student_id'=>$id));
        if(isset($group)) {
            $model->group_id = $group->group_id;
        }
        return $model;
    }

    public function actionBrone($lesson,$day){
        if(Yii::app()->user->role == "student") {
            $lim = $this->getUserLimit(Yii::app()->user->id);
            if($lim>0) {
                $res = Practice::model()->findByAttributes(array('student_id'=>Yii::app()->user->id,'lesson'=>$lesson,'day'=>$day));
                if($res == null) {
                    $model = new Practice();
                    $model->student_id = Yii::app()->user->id;
                    $model->lesson = $lesson;
                    $model->day = $day;
                    $model->save();
                }
            }
        }
        $this->redirect('practice');
    }

    public function actionDeleteBrone($lesson,$day){
        if(Yii::app()->user->role == "student") {
                $model = Practice::model()->findByAttributes(array("student_id"=>Yii::app()->user->id,"lesson"=>$lesson,"day"=>$day));
            if($model!=null){
                $model->delete();
            }
        }
        $this->redirect('practice');
    }

    public function getUserLimit($id){
        $practice = Practice::model()->findAllByAttributes(array("student_id"=>$id));
        $group = Group::model()->findBySql("SELECT `group`.* from `group`,student_to_group
            WHERE `group`.group_id = student_to_group.group_id AND student_to_group.student_id=".Yii::app()->user->id);
        return $group->practice_reserv_count - count($practice);
    }

    public function getDaysCount($start_day,$target_day,$autoschool_id){
        $sday = new DateTime($start_day->format('d.m.y'));
        $autoschoolsettings = PracticeSettings::model()->findByPk($autoschool_id);
        $days = UserController::FormatWeekSettings($autoschoolsettings);
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

    public function actionPracticeList(){
        $limit = 0;
        if(Yii::app()->user->role == "student") {
            $group = Group::model()->findBySql("SELECT `group`.* from `group`,student_to_group
            WHERE `group`.group_id = student_to_group.group_id AND student_to_group.student_id=".Yii::app()->user->id);
            $practice = Practice::model()->findallbysql("SELECT practice.* from practice where practice.student_id=".Yii::app()->user->id.
            " ORDER BY practice.day,practice.lesson;");
            $start = new DateTime($group->practice_start);
            foreach($practice as $item){ // формируем правильное время
                $item->group = $group;
                $item->start_time = new DateTime($group->practice_start);
                $item->start_time->setTime(0,0,0);
                $item->start_time->modify("+".$this->getDaysCount($start,$item->day,$group->autoschool_id)." days");
                $sadd = $this->getTime($group->autoschool_id,$item->lesson);
                $item->start_time->modify("+".$sadd." seconds");
                $item->end_time = new DateTime($item->start_time->format('d.m.Y H.i.s'));
                $item->end_time->modify("+".$this->getDuration($group->autoschool_id)." seconds");
            }
            $limit = $this->getUserLimit(Yii::app()->user->id);
            $this->render('practicelist',array('model'=>$practice,'limit'=>$limit));
        }
        else if(Yii::app()->user->role == "teacher"){
            $practice = Practice::model()->findallbysql("SELECT practice.* from practice,student_to_group,`group` WHERE
            practice.student_id=student_to_group.student_id AND student_to_group.group_id=`group`.group_id AND `group`.practice_teacher=".Yii::app()->user->id);
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
            $this->render('practicelist',array('model'=>$practice));
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

    public function actionPractice()
    {
        if(Yii::app()->user->role == "student"){
            $group = Group::model()->findBySql("SELECT `group`.* from `group`,student_to_group
            WHERE `group`.group_id = student_to_group.group_id AND student_to_group.student_id=".Yii::app()->user->id);
            $id = $group->group_id;
            $limit = $this->getUserLimit(Yii::app()->user->id);

        }else {
            if (isset($_POST['groupDropDown'])) {
                $id = $_POST['groupDropDown'];
            }
            $group = Group::model()->findByPk($id); // текущая группа, также содержит настройки практики (начало, кол-во дней)
            $limit =0;
        }
        if($group != null) {

            //получаем список всех практик студентов, которые учаться в текущей группе
            $sql = "SELECT practice.* FROM practice,student_to_group WHERE
                    practice.student_id=student_to_group.student_id AND student_to_group.group_id=" . $id . ";";
            $practice = Practice::model()->findAllBySql($sql);

            $settings = PracticeSettings::model()->findByAttributes(array('autoschool_id' => $group->autoschool_id));//настройки практики для тек школы
            $date = new Datetime($group->practice_start);
            $weeks = UserController::FormatWeekSettings($settings); // форматированый спсок недель

            //формирование шапки (даты)
            $resdates = array();
            if ($group->practice_days > 0 && $weeks != null) {
                for ($i = 0; $i < $group->practice_days; $i++) {
                    if ($weeks[$date->format("w")] == true) {
                        $resdates[$i] = new DateTime($date->format("y-m-d " . "00:00:00"));
                    } else {
                        $i--;
                    }
                    $date->modify("+1 days");
                }
            }
            // формирование первой колонки (время)
            $fstartlessons = UserController::FormatLessonTime($settings); // список времени
            $fresstartlesson = array(); // список который не отображает секунды
            for ($i = 0; $i < count($fstartlessons); $i++) {
                $bdate = new DateTime($fstartlessons[$i]);
                $fresstartlesson[$i] = $bdate->format("H:i");
            }

            $fendlesson = array();
            $addinterval = new DateTime($settings->duration);
            $addseconds = ((int)$addinterval->format("h")) * 3600 + ((int)$addinterval->format("i")) * 60 + ((int)$addinterval->format("s")); // высчитываем секунды
            for ($i = 0; $i < count($fstartlessons); $i++) {
                $ldate = new DateTime($fstartlessons[$i]);
                $ldate->modify("+" . $addseconds . " seconds");
                $fendlesson[$i] = $ldate->format("H:i");
            }

            $practiceres = array();

            for ($z = 0; $z < count($practice); $z++) { // перебираем все зарезервированные места
                $practiceres[$practice[$z]->lesson][$practice[$z]->day] = $practice[$z];
            }
        }
        $groups = UserController::GetGroupList();
        $this->render('practice',array(
            'group'=>$group,
            'groups'=>$groups,
            'dates'=>$resdates,
            'starttime'=>$fresstartlesson,
            'endtime'=>$fendlesson,
            'practice'=>$practiceres,
            'limit'=>$limit,
        ));
    }

    //возврощает массив [№ дня недели] = статус
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

    private static function FormatLessonTime($PracticeSettings){
        $res = array();
        if($PracticeSettings->practice1!="00:00:00") {
            $res[0] = $PracticeSettings->practice1;
        }
        if($PracticeSettings->practice2!="00:00:00") {
            $res[1] = $PracticeSettings->practice2;
        }
        if($PracticeSettings->practice3!="00:00:00") {
            $res[2] = $PracticeSettings->practice3;
        }
        if($PracticeSettings->practice4!="00:00:00") {
            $res[3] = $PracticeSettings->practice4;
        }
        if($PracticeSettings->practice5!="00:00:00") {
            $res[4] = $PracticeSettings->practice5;
        }
        if($PracticeSettings->practice6!="00:00:00") {
            $res[5] = $PracticeSettings->practice6;
        }
        if($PracticeSettings->practice7!="00:00:00") {
            $res[6] = $PracticeSettings->practice7;
        }
        if(count($res)>0){
            return $res;
        }
        return null;
    }

    public static function GetGroupList(){
        if(Yii::app()->user->role == "administrator"){
            return Group::model()->findAll();
        }
        else {
            $user = Yii::app()->user->getUserModel();
            return Group::model()->findAllByAttributes(array('autoschool_id'=>$user->autoschool_id));
        }
    }

}