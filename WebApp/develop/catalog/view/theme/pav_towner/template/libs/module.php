<?php 
/******************************************************
 * @package Pav Opencart Theme Framework for Opencart 1.5.x
 * @version 1.0
 * @author http://www.pavothemes.com
 * @copyright	Copyright (C) Feb 2013 PavoThemes.com <@emai:pavothemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/

	class ThemeControlHelper extends Controller{
		/**
		 *
		 */
		private $positions = array();
		
		/**
		 *
		 */
		private $modulesList = array();
		
		/**
		 *
		 */
		public $cparams = array();
		
		/**
		 *
		 */
		private $layout_id = 0;
		
		/**
		 *
		 */
		private $theme = '';
		
		/**
		 *
		 */	
		private $pageClass = '';

		/**
		 *
		 */
		private $_jsFiles = array();

		private $_cssFiles = array();
		/**
		 * get instance of this 
		 */
		public static function getInstance( $registry, $theme='default'){
			static $_instance;
			if( !$_instance ){
				$_instance = new ThemeControlHelper( $registry, $theme  );
			}
			return $_instance;
		}
		
		/**
		 * Constructor 
		 */
		public function __construct( $registry, $theme ){
		
			$this->positions = array( 'mainmenu',
									  'slideshow',
									  'promotion',
									  'showcase',
									  'content_top',
									  'column_left',
									  'column_right',
									  'content_bottom',
									  'mass_bottom',
									  'footer_top',
									
									  'footer_center',
									  'footer_bottom'
										
			);
		
			parent::__construct( $registry );
			$this->modules = $this->loadModules();
			$this->setConfig( $theme );
			
			$params = array('layout', 'body_pattern','skin') ;
			$config = $this->config->get( 'themecontrol' );
		
			
			$this->addParam( 'skin', $config['skin'] );
			$this->addParam( 'body_pattern', $config['body_pattern'] );
	//		echo 'tienhc';
		// 	$this->addParam( 'body_pattern', $config['body_pattern'] );
			if( 1 ){
				$this->triggerUserParams(  $params );
			}
			$this->language->load( 	'module/themecontrol' );
			$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
			$this->document->addScript('catalog/view/javascript/jquery/tabs.js');
			
			
			$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');
		}
		
		public function setConfig( $theme){
			$this->theme = $theme;
			return $this;
		}
		
		public function addScript( $path ){
			$this->_jsFiles[$path] = $path;
		}

		public function getScriptFiles(){
			return $this->_jsFiles;
		}
		public function triggerUserParams(  $params ){
			
			if( isset($this->request->get['pavreset']) ){ 
				foreach( $params as $param ){
					$kc = $this->theme."_".$param;
					$this->addParam($param,null);	
					setcookie ($kc, null, 0, '/');
					if( isset($_COOKIE[$kc]) ){
						$this->cparams[$kc] = null;
						$_COOKIE[$kc] = null;
					}
				}
				
			}
			
			$exp = time() + 60*60*24*355; 
			foreach( $params as $param ){
				$kc = $this->theme."_".$param;
				if( isset($this->request->post['userparams']) && ($data = $this->request->post['userparams']) ){
					if( isset($data[$param]) ){
						setcookie ($kc, $data[$param], $exp, '/');
						$this->cparams[$kc] = $data[$param];
					}
				}
				if( isset($_COOKIE[$kc]) ){ 
					$this->cparams[$kc] = $_COOKIE[$kc];
				}
			}
			
			if( isset($this->request->post['userparams']) || isset($this->request->get['pavreset'])  ){  
				
				$this->redirect(  $this->url->link("common/home", '', 'SSL') );
			}
		}
		
		public function getParam( $param , $value= '' ){
			return isset($this->cparams[$this->theme."_".$param])?$this->cparams[$this->theme."_".$param]:$value;
		}
		
		/**
		 * add custom parameter 
		 */
		public function addParam( $key, $value ){
			$this->cparams[$this->theme."_".$key] = $value;
		}
		
		/**
		 * get current page class.
		 */
		public function getPageClass(){
			return $this->pageClass ;
		}

		/**
		 * detect layout ID by route in request
		 */
		public function getLayoutId(){
			$this->load->model('catalog/product');
			$this->load->model('catalog/information');
			$this->load->model('design/layout');

			if( !$this->layout_id ) {
				if (isset($this->request->get['route'])) {
					$route = (string)$this->request->get['route'];
					$this->pageClass = 'page-'.str_replace( "/", "-", $route );
				} else {
					$route = 'common/home';
					$this->pageClass = 'page-home';
				}
		
		
				$layout_id = 0;
			
				if ($route == 'product/category' && isset($this->request->get['path'])) {
					$path = explode('_', (string)$this->request->get['path']);
						
					$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));		
					$this->pageClass = 'page-category';		
				}
				
				if ($route == 'product/product' && isset($this->request->get['product_id'])) {
					$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
					$this->pageClass = 'page-product';		
				}
				
				if ($route == 'information/information' && isset($this->request->get['information_id'])) {
					$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
					$this->pageClass = 'page-information';		
				}
				
				if (!$layout_id) {
					$layout_id = $this->model_design_layout->getLayout($route);
				}
						
				if (!$layout_id) {
					$layout_id = $this->config->get('config_layout_id');
				}
			 
				$this->layout_id = $layout_id;
			}
			return $this->layout_id;
		}
		
		/**
		 * load all modules asigned for positions with current layout.
		 */
		public function loadModules (){ 
			$this->load->model('setting/extension');
			$extensions = $this->model_setting_extension->getExtensions('module');		
			$module_data = array();
			$layout_id = $this->getLayoutId();
			foreach ($extensions as $extension) {
				$modules = $this->config->get($extension['code'] . '_module');
				
				if ($modules) {
					foreach ($modules as $module) {  
						if ( ($module['layout_id'] == $layout_id || $module['layout_id'] == '99999' ) && in_array(trim($module['position']), $this->positions) && $module['status']) {
							$module_data[$module['position']][] = array(
								'code'       => $extension['code'],
								'setting'    => $module,
								'sort_order' => $module['sort_order']
							);				
						}
					}
				}
			}
	
			foreach( $module_data as $position => $modules ){
				$sort_order = array(); 
				foreach ($modules as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
				array_multisort($sort_order, SORT_ASC, $module_data[$position]);
			}

			$this->data['modules'] = array();
			$output = array();
			foreach( $module_data as $position => $modules ){
				foreach ($modules as $module) {
					$module = $this->getChild('module/' . $module['code'], $module['setting']);
					
					if ($module) {
						$output[$position][] = $module;
					}
				} 
			} 
			$this->modulesList = $output;
		// 	echo '<pre>'.print_R( $output,1 );die;
		}
		
		/**
		 * get collection of modules by position
		 */
		public function getModulesByPosition( $position ){
			if( isset($this->modulesList[$position]) ){
				return $this->modulesList[$position];
			}
			return ;
		}
		
		/**
		 * caculate span width of column base grid 12 of twitter.
		 * 
		 * @param Array $ospan 
		 * @param Numberic $cols number of columns
		 */
		public function calculateSpans( $ospans=array(), $cols ){
			$tmp = array_sum($ospans);
			$spans = array();
			$t = 0; 
			for( $i=1; $i<= $cols; $i++ ){
				if( array_key_exists($i,$ospans) ){
					$spans[$i] = 'span'.$ospans[$i];
					
				}else{		
					if( (12-$tmp)%($cols-count($ospans)) == 0 ){
						$spans[$i] = "span".((12-$tmp)/($cols-count($ospans)));
						
					}else {
						if( $t == 0 ) {
							$spans[$i] = "span".( floor((11-$tmp)/($cols-count($ospans))) + 1 ) ;
						}else {
							$spans[$i] = "span".( floor((11-$tmp)/($cols-count($ospans))) + 0 ) ;
						}
						$t++;
					}					
				}
			}
			return $spans;
		}
	}
?>