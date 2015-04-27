<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <?php Yii::app()->bootstrap->register(); ?>
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<meta name='wmail-verification' content='44b0e6ec9b487535' />
</head>

<body>

<table class="header">
    <tr>
    <td><!--a class="btn btn-warning app" url="<?php echo Yii::app()->getBaseUrl(true)."/files/autoschool.apk";?>">Android App</a--></td>
    <td><a href="<?php echo Yii::app()->getBaseUrl(true);?>"><img src="<?php echo Yii::app()->getBaseUrl(true);?>/images/logo.png" /></a></td>
    <td class="userpanel">
        <div class="loginform">
            <?if(Yii::app()->user->isGuest): ?>
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id'=>'login-form',
                'enableClientValidation'=>true,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                ),
                'action'=>$this->createUrl('site/login'),
            )); ?>

            <div class="row">
                Логин:<input type="text" name="login"/>
            </div>

            <div class="row">
                Пароль:<input type="password" name="password"/>
            </div>

            <div class="row rememberMe">
                Запомнить меня:<input type="checkbox" name="rememberme"/>
            </div>

            <div class="row buttons">
                <?php echo TbHtml::submitButton('Войти'); ?>
            </div>

            <?php $this->endWidget(); ?>
            <?php else:?>
                Добро пожаловать, <?php echo Yii::app()->user->getUserModel()->login ?><br />
                <a href="<?php echo Yii::app()->createUrl('/user/')?>">Профиль</a>
                <a href="<?php echo Yii::app()->createUrl('site/logout')?>">Выход</a>
            <?php endif; ?>
    </td>
    </tr>
</table>
<div class="container" id="page">
	<div id="mainmenu">
        <?php $this->widget('bootstrap.widgets.TbNavbar', array(
            'brandLabel' => '',
            'display' => null, // default is static to top
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbNav',
                    'items' => array(
                        array('label'=>'Главная', 'url'=>array('/site/index')),
                        array('label'=>'Автошколы', 'url'=>array('/site/autoschool')),
                        array('label'=>'О нас', 'url'=>array('/site/page', 'view'=>'about')),
                        //array('label'=>'Связаться с нами', 'url'=>array('/site/contact')),
                        array('label'=>'Панель управления', 'url'=>array('/admin/'), 'visible'=>Yii::app()->user->checkAccess('moderator')),
                        array('label'=>'Расписание', 'url'=>array('/user/theory'), 'visible'=>Yii::app()->user->role=="student" || Yii::app()->user->role=="teacher"),
                        array('label'=>'Вход', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                        array('label'=>'Регистрация', 'url'=>array('/site/registration'), 'visible'=>Yii::app()->user->isGuest),
                    ),
                ),
            ),
        ));?>
		<?php /*<?php $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Главная', 'url'=>array('/site/index')),
                array('label'=>'Автошколы', 'url'=>array('/site/autoschool', 'view'=>'about')),
				array('label'=>'О нас', 'url'=>array('/site/page', 'view'=>'about')),
				//array('label'=>'Связаться с нами', 'url'=>array('/site/contact')),
                array('label'=>'Панель управления', 'url'=>array('/admin/'), 'visible'=>Yii::app()->user->checkAccess('moderator')),
				array('label'=>'Вход', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                array('label'=>'Регистрация', 'url'=>array('/site/registration'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Выход ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>*/?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by <b>IKS Team</b>.<br/>
		All Rights Reserved.<br/>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
