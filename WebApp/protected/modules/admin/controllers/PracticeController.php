<?php

class PracticeController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//admin/layouts/column2';

    public function beforeAction($action)
    {
        if (parent::beforeAction($action))
        {
            if (Yii::app()->user->checkAccess('moderator')==false)
            {
                Yii::app()->request->redirect(Yii::app()->homeUrl);
            }
            return true;
        }
        else
            return false;
    }

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','view','create','update','updatePractice'),
                'roles'=>array('moderator'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'roles'=>array('administrator'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * @param integer $id - id of group
	 */
	public function actionView()
	{
        if(isset($_POST['groupDropDown'])){
            $id = $_POST['groupDropDown'];
        }
        $group = Group::model()->findByPk($id); // текущая группа, также содержит настройки практики (начало, кол-во дней)
        if($group != null) {

            //получаем список всех практик студентов, которые учаться в текущей группе
            $sql = "SELECT practice.* FROM practice,student_to_group WHERE
                    practice.student_id=student_to_group.student_id AND student_to_group.group_id=" . $id . ";";
            $practice = Practice::model()->findAllBySql($sql);

            $settings = PracticeSettings::model()->findByAttributes(array('autoschool_id' => $group->autoschool_id));//настройки практики для тек школы
            $date = new Datetime($group->practice_start);
            $weeks = PracticeController::FormatWeekSettings($settings); // форматированый спсок недель

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
            $fstartlessons = PracticeController::FormatLessonTime($settings); // список времени
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
        $groups = PracticeController::GetGroupList();
		$this->render('view',array(
            'group'=>$group,
            'groups'=>$groups,
			'dates'=>$resdates,
            'starttime'=>$fresstartlesson,
            'endtime'=>$fendlesson,
            'practice'=>$practiceres,
		));
	}

    //возврощает массив [№ дня недели] = статус
    private static function FormatWeekSettings($PracticeSettings){
        $res = array();
        $res[1] = $PracticeSettings->monday==1;
        $res[2] = $PracticeSettings->tuesday==1;
        $res[3] = $PracticeSettings->wednesday==1;
        $res[4] = $PracticeSettings->thursday==1;
        $res[5] = $PracticeSettings->friday==1;
        $res[6] = $PracticeSettings->saturday==1;
        $res[0] = $PracticeSettings->sunday==1;
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
        if(Yii::app()->user->role == "moderator"){
            $user = Yii::app()->user->getUserModel();
            return Group::model()->findAllByAttributes(array('autoschool_id'=>$user->autoschool_id));
        }
        else {
            return Group::model()->findAll();
        }
    }
}
