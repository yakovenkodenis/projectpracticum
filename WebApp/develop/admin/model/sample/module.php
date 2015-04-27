<?php
class ModelSampleModule extends Model {
	
	public function getSamplesByTheme( $theme ){
		$this->load->model('setting/extension');
		$extensions = $this->model_setting_extension->getInstalled('module');
		
		$output = array();
		$files = glob(  dirname(__FILE__).'/'.$theme.'/*.php');
		foreach( $files as $dir ){
			$module = str_replace(".php","",basename( $dir ));
			if( !is_file(DIR_APPLICATION."controller/module/".$module.".php") ){	
				$moduleName = $module;
				$existed = 0;
			}else {
				$this->language->load( 'module/'.$module );		
				$moduleName = $this->language->get('heading_title');
				$existed = 1;
			}
			
			$data = $this->config->get($module . '_module');
			 
			$output[$module] = array('extension_installed' => in_array($module,$extensions), 
									 "module"    => $module ,
									 'existed'   => $existed, 
									 'installed' => empty($data)?0:1, 
									 'moduleName'=> $moduleName );
	
		}		
		return $output;
	}
	
	public function getModulesQuery( $theme ){
		if( is_file(dirname(__FILE__).'/'.$theme.'.php') ) {
			require( dirname(__FILE__).'/'.$theme.'.php' );
			$dir = dirname(__FILE__).'/'.$theme.'/';
			
			$this->load->model('setting/extension');
			
			
			$query =  ModuleSample::getModulesQuery();
			
			$modules = array();
			$extensions = $this->model_setting_extension->getInstalled('module');
			
	
			foreach( $query as $key=>$q ) {
				if(  in_array($key,$extensions) ){
					$this->language->load('module/' . $key);
					$modules[$key] = $this->language->get( 'heading_title' );
				}
			}
			
			
			// echo '<pre>'.print_r( $module ); die;
			return $modules;
		}	
		return array();
	}
	
	public function export( $theme ) {
		if( is_file(dirname(__FILE__).'/'.$theme.'.php') ) {
			require( dirname(__FILE__).'/'.$theme.'.php' );
			$dir = dirname(__FILE__).'/'.$theme.'/';
			$modules = ModuleSample::getModules();
			if( isset($modules) ){
				foreach( $modules as $module ){
					$data = serialize($this->config->get($module . '_module'));
					$fp = fopen( $dir.$module.'.php', 'w');
					fwrite($fp, $data );
					fclose($fp);
				}
		
			}
		}
	}
	
	public function installSampleQuery( $theme, $module ){
		if( is_file(dirname(__FILE__).'/'.$theme.'.php') ) {
			require( dirname(__FILE__).'/'.$theme.'.php' );
			$dir = dirname(__FILE__).'/'.$theme.'/';
			
			$this->load->model('setting/extension');
			
			
			$query =  ModuleSample::getModulesQuery();
			
			$module = isset( $this->request->get['module'])? trim( $this->request->get['module']):"" ; 
			$installFile = DIR_APPLICATION.'model/'.$module.'/install.php';
			/** automatic check installation of this before overriding new datasample  **/ 
			if( $module && file_exists($installFile) ){
				$this->load->model( $module."/install" );
				$tm = $this->{"model_".$module."_install"}; 
				if( is_object($tm) && method_exists( $tm, "checkInstall" ) ){
					$tm->checkInstall();
				}
				if( method_exists("ModuleSample","installCustomSetting") ){
					ModuleSample::installCustomSetting( $this );
				}
			}
			
			
			$modules = array();
			 
			if( isset($query[$module]) ){
				foreach( $query[$module] as $query ){
					$this->db->query( $query );
				}
				die('done');
			}
			
		}	
		die( 'could not install data sample for this' );
	}
	public function installStoreSample( $theme ){
		if( is_file(dirname(__FILE__).'/'.$theme.'.php') ) {
			require( dirname(__FILE__).'/'.$theme.'.php' );
			$dir = dirname(__FILE__).'/'.$theme.'/';
			$configs = ModuleSample::getStoreConfigs();
			if( isset($configs) ){
				$this->load->model('setting/setting');
				foreach( $configs as $key => $value ){
					$group = 'config';
					$store_id = 0;
					
				//	 $this->model_setting_setting->editSettingValue( 'config', $key, $value );			
					$this->db->query("UPDATE " . DB_PREFIX . "setting SET `value` = '" . $this->db->escape($value) . "' WHERE `group` = '" . $this->db->escape($group) . "' AND `key` = '" . $this->db->escape($key) . "' AND store_id = '" . (int)$store_id . "'");
				}
			}
		}
	}
	
	public function installSample( $theme, $module ){
		$this->load->model('setting/setting');
		$dir = dirname(__FILE__).'/'.$theme.'/'; 
	
		if( is_file($dir.$module.'.php') ){
			$data = unserialize(file_get_contents( $dir.$module.'.php' ));

			if( is_array($data) ){
				$output = array();
				$output[$module."_module"] = $data; 
				
				 $this->model_setting_setting->editSetting( $module, $output );	
			}
		}	 
	}
}
?>