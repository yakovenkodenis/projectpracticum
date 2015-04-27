<?php

class AutoschoolController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//admin/layouts/column2';
    public $defaultAction = 'admin';

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
				'actions'=>array('index','view','update','updatePractice'),
                'roles'=>array('moderator'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','admin','delete'),
				'roles'=>array('administrator'),
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
		$model=new Autoschool;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Autoschool']))
		{
			$model->attributes=$_POST['Autoschool'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{
        if(Yii::app()->user->role == "moderator") {
            $usr = User::model()->findByPk(Yii::app()->user->id);
            $id = $usr->autoschool_id;
        }
        else {
            $id = $_GET['id'];
        }
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Autoschool']))
		{
			$model->attributes=$_POST['Autoschool'];
			if($model->save()) {
                if(Yii::app()->user->role == "moderator"){
                    Yii::app()->user->setFlash('result','Данные сохранены');
                    $this->redirect(array('./'));
                }
                else {
                    $this->redirect(array('admin'));
                }
            }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

    public function actionUpdatePractice()
    {
        if (Yii::app()->user->role == "moderator") {
            $usr = User::model()->findByPk(Yii::app()->user->id);
            $id = $usr->autoschool_id;
        } else {
            $id = $_GET['id'];
        }
        $model = $this->loadSettingsModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PracticeSettings'])) {
            $model->attributes = $_POST['PracticeSettings'];
            //проверяем установленное время и правим его в случае ошибок
            if ($model->save())
                if(Yii::app()->user->role == "moderator"){
                    Yii::app()->user->setFlash('result','Данные сохранены');
                    $this->redirect(array('./'));
                }
                else {
                    $this->redirect(array('admin'));
                }
        }

        $this->render('updatepractice', array(
            'model' => $model,
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
		$dataProvider=new CActiveDataProvider('Autoschool');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Autoschool('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Autoschool']))
			$model->attributes=$_GET['Autoschool'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Autoschool the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Autoschool::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

    public function loadSettingsModel($id)
    {
        $model=PracticeSettings::model()->findByPk($id);
        if($model===null) {
            $model = new PracticeSettings();
            $model->autoschool_id = $id;
            $model->practice1 = "00:00:00";
            $model->practice2 = "00:00:00";
            $model->practice3 = "00:00:00";
            $model->practice4 = "00:00:00";
            $model->practice5 = "00:00:00";
            $model->practice6 = "00:00:00";
            $model->practice7 = "00:00:00";
        }
        return $model;
    }

	/**
	 * Performs the AJAX validation.
	 * @param Autoschool $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='autoschool-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
