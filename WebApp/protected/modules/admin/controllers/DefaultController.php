<?php

class DefaultController extends Controller
{
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

	public function actionIndex()
	{
        $this->pageTitle = "Панель управления";
		$this->render('index');
	}
}