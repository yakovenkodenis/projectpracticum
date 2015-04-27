<?php

class TheoryController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//admin/layouts/column2';
    public $defaultAction = 'admin';

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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','Grouplist'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'roles'=>array('moderator'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Theory;
        $teachers = TheoryController::GetTeacherList();
        $groups = TheoryController::GetGroupList();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Theory']))
		{
			$model->attributes=$_POST['Theory'];
			if($model->save())
				$this->redirect(array('admin','id'=>$model->theory_id));
		}

		$this->render('create',array(
			'model'=>$model,
            'teachers'=>$teachers,
            'groups'=>$groups,
		));
	}

    public static function GetTeacherList(){
        if(Yii::app()->user->role == "moderator"){
            return User::model()->findAllByAttributes(array("role" => "teacher","autoschool_id"=>Yii::app()->user->getUserModel()->autoschool_id));
        }
        else {
            return User::model()->findAllByAttributes(array("role" => "teacher"));
        }
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
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Theory']))
		{
			$model->attributes=$_POST['Theory'];
			if($model->save())
				$this->redirect(array('admin','id'=>$model->theory_id));
		}

        $teachers = TheoryController::GetTeacherList();
        $groups = TheoryController::GetGroupList();

		$this->render('update',array(
			'model'=>$model,
            'teachers'=>$teachers,
            'groups'=>$groups,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Theory');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Theory('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Theory']))
			$model->attributes=$_GET['Theory'];
        $listautoschool = CHtml::listData(Autoschool::model()->findall(),'id','name');
        $group = TheoryController::GetGroupList();
        $teachers = CHtml::listData(TheoryController::GetTeacherList(), 'id', 'name');
		$this->render('admin',array(
			'model'=>$model,
            'autoschool'=>$listautoschool,
            'teachers'=>$teachers,
            'group'=>$group,
		));
	}

    //AJAX подгрузка списка групп
    public function actionGrouplist()
    {
        if(Yii::app()->request->isAjaxRequest) {
            if ($_POST['autoschoolid']) {
                $listgroup = Group::model()->findAllByAttributes(array('autoschool_id' => $_POST['autoschoolid']));
                $res = '<option value="-1">(Выберите группу)</option>';
                foreach ($listgroup as $value) {
                    $res .= '<option value="' . $value->group_id . '">' . $value->name . '</option>';
                }
                echo $res;
            }
        }
    }
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Theory the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Theory::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Theory $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='theory-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
