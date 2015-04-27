<?php

class SiteController extends Controller
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
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
        //if(Yii::app()->user->checkAccess('administrator')){
        //    echo "hello, I'm administrator";
        //}
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
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

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
        if(isset($_POST['login'])&&isset($_POST['password'])){
            $model->username = $_POST['login'];
            $model->password = $_POST['password'];
            $model->rememberMe = $_POST['rememberMe'];
            if($model->login())
                $this->redirect(Yii::app()->user->returnUrl);
            else{
                $this->redirect('login');
            }
        }
		// display the login form
		$this->render('login',array('model'=>$model));
	}

    public function ActionRegistration()
    {
        $model=new User;
        $model->scenario = 'registration';
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            $result = $this->GetAutschoolByCode($model->autoschoolCode);
            if(isset($result)){
                if($result->studentCode == $model->autoschoolCode){
                    $model->autoschool_id = $result->id;
                    $model->role = 'student';
                }
                else if($result->teacherCode == $model->autoschoolCode){
                    $model->autoschool_id = $result->id;
                    $model->role = 'teacher';
                }
                else{
                    $model->role = 'user';
                }
            }
            else{
                $model->role = 'user';
            }
            if($model->save()) {
                Yii::app()->user->setFlash('registration','Вы успешно зарегестрированы!');
                $this->redirect(array('registration'));
            }
        }
        $this->render('registration',array(
            'model'=>$model,
        ));
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

    public function actionAutoschool()
    {
        $school = null;
        if(isset($_GET['id'])){
            $school = Autoschool::model()->findbyPk($_GET['id']);
        }
        $dataProvider=new CActiveDataProvider('Autoschool');
        $autoschool = Autoschool::model()->findall();
        $this->render('autoschool',array(
            'dataProvider'=>$dataProvider,
            'autoschool'=>$autoschool,
            'school'=>$school,
        ));
    }

    public function actionDetailview($id)
    {
        $this->render('detailview',array(
            'model'=>$this->loadAutoschoolModel($id),
        ));
    }
    public function loadAutoschoolModel($id)
    {
        $model=Autoschool::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
    /**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}