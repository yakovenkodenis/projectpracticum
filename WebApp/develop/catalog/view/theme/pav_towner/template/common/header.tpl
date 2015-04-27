<?php
/******************************************************
 * @package Pav Opencart Theme Framework for Opencart 1.5.x
 * @version 1.0
 * @author http://www.pavothemes.com
 * @copyright	Copyright (C) Feb 2013 PavoThemes.com <@emai:pavothemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/
	$themeConfig = $this->config->get( 'themecontrol' );
	$themeName =  $this->config->get('config_template');
	require_once( DIR_TEMPLATE.$this->config->get('config_template')."/template/libs/module.php" );
	$helper = ThemeControlHelper::getInstance( $this->registry, $themeName );


	/* Add scripts files */
	$helper->addScript( 'catalog/view/javascript/jquery/jquery-1.7.1.min.js' );
	$helper->addScript( 'catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js' );
	$helper->addScript( 'catalog/view/javascript/jquery/ui/external/jquery.cookie.js' );
	$helper->addScript( 'catalog/view/javascript/common.js' );
	$helper->addScript( 'catalog/view/theme/'.$themeName.'/javascript/common.js' );
	$helper->addScript( 'catalog/view/javascript/jquery/bootstrap/bootstrap.min.js' );

?>
<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
<meta name="viewport" content="width=device-width">
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/stylesheet.css" />
<style type="text/css">
	<?php if( $themeConfig['theme_width'] &&  $themeConfig['theme_width'] != 'auto' ) { ?>
			#page-container .container{max-width:<?php echo $themeConfig['theme_width'];?>; width:auto}
	<?php } ?>
	
	<?php if( isset($themeConfig['use_custombg']) && $themeConfig['use_custombg'] ) {	?>
		body{
			background:url( "image/<?php echo $themeConfig['bg_image'];?>") <?php echo $themeConfig['bg_repeat'];?>  <?php echo $themeConfig['bg_position'];?> !important;
		}
	<?php } ?>
	<?php 
		if( isset($themeConfig['custom_css'])  && !empty($themeConfig['custom_css']) ){
			echo trim( html_entity_decode($themeConfig['custom_css']) );
		}
	?>
</style>
<?php 
	if( isset($themeConfig['enable_customfont']) && $themeConfig['enable_customfont'] ){
	$css=array();
	$link = array();
	for( $i=1; $i<=3; $i++ ){
		if( trim($themeConfig['google_url'.$i]) && $themeConfig['type_fonts'.$i] == 'google' ){
			$link[] = '<link rel="stylesheet" type="text/css" href="'.trim($themeConfig['google_url'.$i]) .'"/>';
			$themeConfig['normal_fonts'.$i] = $themeConfig['google_family'.$i];
		}
		if( trim($themeConfig['body_selector'.$i]) && trim($themeConfig['normal_fonts'.$i]) ){
			$css[]= trim($themeConfig['body_selector'.$i])." {font-family:".str_replace("'",'"',htmlspecialchars_decode(trim($themeConfig['normal_fonts'.$i])))."}\r\n"	;
		}
	}
	echo implode( "\r\n",$link );
?>
<style>
	<?php echo implode("\r\n",$css);?>
</style>
<?php } else { ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/font.css" />

<?php if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) { ?>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
<?php } else { ?>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
<?php } ?>
<?php } ?>
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<?php if( $helper->getParam('skin') &&  $helper->getParam('skin') != 'default' ){ ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/skins/<?php echo  $helper->getParam('skin');?>/stylesheet/stylesheet.css" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/font-awesome.min.css" />
<?php if( isset($themeConfig['responsive']) && $themeConfig['responsive'] ){ ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/theme-responsive.css" />
<?php } ?>
<?php if( $direction == 'rtl' ) { ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/theme-rtl.css" />
<?php } ?>

<?php foreach( $helper->getScriptFiles() as $script )  { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>

<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>

<?php if( isset($themeConfig['custom_javascript'])  && !empty($themeConfig['custom_javascript']) ){ ?>
	<script type="text/javascript"><!--
		$(document).ready(function() {
			<?php echo html_entity_decode(trim( $themeConfig['custom_javascript']) ); ?>
		});
//--></script>
<?php }	?>
<!--[if IE 8]>         
 <link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/ie8.css" />
<![endif]-->
<!--[if lt IE 9]>
<?php if( isset($themeConfig['load_live_html5'])  && $themeConfig['load_live_html5'] ) { ?>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<?php } else { ?>
<script src="catalog/view/javascript/html5.js"></script>
<?php } ?>
<![endif]-->
<?php if( isset($themeConfig['enable_paneltool']) && $themeConfig['enable_paneltool'] ){  ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/<?php echo $themeName;?>/stylesheet/paneltool.css" />
<?php } ?>

<?php if ( isset($stores) && $stores ) { ?>
<script type="text/javascript"><!--
$(document).ready(function() {
<?php foreach ($stores as $store) { ?>
$('body').prepend('<iframe src="<?php echo $store; ?>" style="display: none;"></iframe>');
<?php } ?>
});
//--></script>
<?php } ?>
<?php echo $google_analytics; ?>
</head>
<body class="fs<?php echo $themeConfig['fontsize'];?> <?php echo $helper->getPageClass();?> <?php echo $helper->getParam('body_pattern','');?>">
<div id="page-container">
<header id="header">
	<div id="headertop">
		<div class="container">
		<div class="container-inner">
			<div class="hidden-tablet hidden-phone">
				<div class="login pull-left">
						<?php if (!$logged) { ?>
						<?php echo $text_welcome; ?>
						<?php } else { ?>
						<?php echo $text_logged; ?>
						<?php } ?> 
				</div>
				
				<div class="links  pull-left">
					<!--<a class="first" href="<?php //echo $home; ?>"><?php // echo $text_home; ?></a>-->
					<a class="account" href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
					<a class="wishlist" href="<?php echo $wishlist; ?>" id="wishlist-total"><?php echo $text_wishlist; ?></a>
					<!--<a href="<?php //echo $shopping_cart; ?>"><?php //echo $text_shopping_cart; ?></a>-->
					<a class="last checkout" href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a>
					
				</div>
				<div class="pull-right">
					<div class="currency  pull-right">
						<?php echo $currency; ?>
					</div> 
					<?php /*<div class="language pull-right">
						<?php echo $language; ?>
					</div> */ ?>
				</div>
			</div>
									
			<div class="show-mobile hidden-desktop  pull-right">
				<div class="quick-user pull-left">
							<div class="quickaccess-toggle">
								<i class="fa fa-user"></i>															
							</div>	
							<div class="inner-toggle">
								<div class="login">
									<?php if (!$logged) { ?>
									<?php echo $text_welcome; ?>
									<?php } else { ?>
									<?php echo $text_logged; ?>
									<?php } ?> 
								</div>
							</div>						
						</div>
						<div class="quick-access pull-left">
							<div class="quickaccess-toggle">
								<i class="fa fa-list"></i>															
							</div>	
							<div class="inner-toggle">
								<ul class="pull-left">
									<!-- <li><a class="first" href="<?php echo $home; ?>"><?php echo $text_home; ?></a></li> -->
									<li><a class="account" href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
									<li><a class="wishlist" href="<?php echo $wishlist; ?>" id="mobile-wishlist-total"><?php echo $text_wishlist; ?></a></li>
									<li><a class="shoppingcart" href="<?php echo $shopping_cart; ?>"><?php echo $text_shopping_cart; ?></a></li>
									<li><a class="last checkout" href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></li> 
						
								</ul>
							</div>						
						</div>


						<div id="search_mobile" class="search pull-left">				
							<div class="quickaccess-toggle">
								<i class="fa fa-search"></i>								
							</div>																								
							<div class="inner-toggle">
							
								<div id="search-m">
						<input type="text" name="search1" placeholder="<?php echo $text_search; ?>" value="<?php echo $search; ?>" />
							<span class="button-search">Search</span>
					</div>

							</div>
						</div>


						<div class="currency-mobile pull-left">
							<div class="quickaccess-toggle">
								<i class="fa fa-calendar"></i>								
							</div>						
							<div class="inner-toggle">
								<div class="currency pull-left">
									<?php echo $currency; ?>
								</div> 
							</div>															
						</div>
						
						
						<div class="language-mobile pull-left">
							<div class="quickaccess-toggle">
								<i class="fa fa-cog"></i>								
							</div>						
							<div class="inner-toggle">	
								<div class="language pull-left">
									<?php echo $language; ?>
								</div>
							</div>															
						</div>
						
			</div>	
			
		</div>
	</div>
	</div>
	<div id="headerbottom">
		<div class="container">
		<div class="container-inner">
			<div class="row-fluid">
				<div class="span4 logo">
					  <?php if ($logo) { ?>
					  <div id="logo"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
					  <?php } ?>
				</div>
				<div class="span8 search-cart hidden-tablet hidden-phone">
					<ul id="telephones" class="pull-left">
						<li>+38(067)-593-43-60</li>
						<li>+38(050)-688-33-38</li>
						<li>+38(063)-356-43-35</li>
					</ul>
					<div id="search" class="pull-right" >
						<input type="text" name="search" placeholder="<?php echo $text_search; ?>" value="<?php echo $search; ?>" />
						<span class="button-search">search</span>
					</div>
					<?php echo $cart; ?>
				</div>
				</div>
			</div>
		</div>
	</div>
</header>

<section id="mainnav">
	<div class="container">
		<div class="mainnav-inner">
			<div class="row-fluid">
				<?php 
				/**
				 * Main Menu modules: as default if do not put megamenu, the theme will use categories menu for main menu
				 */
				$modules = $helper->getModulesByPosition( 'mainmenu' ); 
				if( count($modules) ){ 
				?>

						<?php foreach ($modules as $module) { ?>
						<nav id="mainmenu" class="span12">	<?php echo $module; ?></nav>
						<?php } ?>

				<?php } elseif ($categories) { ?>
				<nav id="mainmenu" class="span12">
					<div class="navbar">
						<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						  <span class="icon-bar"></span>
						  <span class="icon-bar"></span>
						  <span class="icon-bar"></span>
						</a>
						<div class="navbar-inner">
							<div class="nav-collapse collapse">
									
								  <ul class="nav">
								  <li ><a href="#">Главная</a>
								  <li ><a href="#">Новинки</a>
								  <li ><a href="#">Новости</a>
								  <li ><a href="#">Вопросы и ответы</a>
								  <li ><a href="#">Доставка и оплата</a>
								  <li ><a href="#">Контакты</a>
								  <li ><a href="#">Отзывы</a>
									<?php /*foreach ($categories as $category) { ?>
									
									<?php if ($category['children']) { ?>			
									<li class="parent dropdown deeper "><a href="<?php echo $category['href'];?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?>
									<b class="caret"></b>
									</a>
									<?php } else { ?>
									<li ><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
									<?php } ?>
									<?php if ($category['children']) { ?>
									  <ul class="dropdown-menu">
										<?php for ($i = 0; $i < count($category['children']);) { ?>
										
										  <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
										  <?php for (; $i < $j; $i++) { ?>
										  <?php if (isset($category['children'][$i])) { ?>
										  <li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
										  <?php } ?>
										  <?php } ?>
										
										<?php } ?>
									</ul>
									  <?php } ?>
									</li>
									<?php } */?>
								  </ul>
							</div>	
						</div>		  
					</div>
				</nav>
				<?php } ?>

			</div>
		</div>
	</div>
</section>
<?php
/**
 * Slideshow modules
 */
$modules = $helper->getModulesByPosition( 'slideshow' ); 
if( $modules ){
?>
<section id="slideshow" class="pav-slideshow">
	<div class="container">
		<?php foreach ($modules as $module) { ?>
			<?php echo $module; ?>
		<?php } ?>
	</div>
</section>
<?php } ?>


<section id="sys-notification"><div class="container"><div id="notification"></div></div></section>
<section id="columns"><div class="container"><div class="row-fluid">