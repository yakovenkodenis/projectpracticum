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
</head>

<body>

<div class="container" id="page">

	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?> - Панель управления</div>
	</div><!-- header -->

	<div id="mainmenu">
        <?php $this->widget('bootstrap.widgets.TbNavbar', array(
            'brandLabel' => 'На сайт',
            'display' => null, // default is static to top
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.TbNav',
                    'items'=>array(
                        array('label'=>'Главная', 'url'=>array('//admin')),
                        array(
                            'label' => 'Автошколы',
                            'items' => array(
                                array('label' => 'Список автошкол', 'url' => Yii::app()->createUrl('/admin/autoschool/admin'), 'visible'=>Yii::app()->user->checkAccess('administrator')),
                                array('label' => 'Создать автошколу', 'url' => Yii::app()->createUrl('/admin/autoschool/create'), 'visible'=>Yii::app()->user->checkAccess('administrator')),
                                array('label' => 'Моя автошкола', 'url' => Yii::app()->createUrl('/admin/autoschool/update'), 'visible'=>Yii::app()->user->role == "moderator"),
                                array('label' => 'Список групп', 'url' => Yii::app()->createUrl('/admin/group/admin')),
                                array('label' => 'Создать группу', 'url' => Yii::app()->createUrl('/admin/group/create')),
                            ),
                        ),
                        array(
                            'label' => 'Расписание',
                            'items' => array(
                                array('label' => 'Теоретические занятия', 'url' => Yii::app()->createUrl('/admin/theory/admin')),
                                array('label' => 'Создать теоретическое занятие', 'url' => Yii::app()->createUrl('/admin/theory/create')),
                                array('label' => 'Практические занятия', 'url' => Yii::app()->createUrl('/admin/practice/view'))
                            )
                        ),
                        array(
                            'label' => 'Пользователи',
                            'items' => array(
                                array('label' => 'Список', 'url' => Yii::app()->createUrl('/admin/user/admin')),
                                array('label' => 'Создать', 'url' => Yii::app()->createUrl('/admin/user/create'))
                            )
                        ),
                    ),
                ),
            ),
        ));?>
		<?php/* $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Главная', 'url'=>array('//admin')),
				array('label'=>'Автошколы', 'url'=>array('//admin/autoschool')),
				array('label'=>'Пользователи', 'url'=>array('//admin/user')),
                array('label'=>'Вернуться на сайт', 'url'=>array('/site/index')),
			),
		)); */?>
	</div><!-- mainmenu -->
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> by <b>VIKS Team</b>.<br/>
		All Rights Reserved.<br/>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
