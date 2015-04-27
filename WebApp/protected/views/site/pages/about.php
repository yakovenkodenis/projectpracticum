<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - About';
$this->widget('bootstrap.widgets.TbBreadcrumb', array(
    'links' => array(
	'О нас',
    ),
));
?>
<h1>О нас</h1>

<h4>Данный проект разработан командой разработчиков IKS</h4>

<h4>Состав:</h4>
<ul>
    <li><b>Крючков Алексей</b> - разработка и проектирование Back-end части сайта</li>
    <li><b>Яковенко Денис</b> - разработка и проектирование Android приложения</li>
    <li><b>Савченко Дмитрий</b> - разработка и проектирование Front-end части сайта</li>
 </ul>