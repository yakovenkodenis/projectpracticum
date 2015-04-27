<?php
class ControllerModuleBrands extends Controller {
   protected function index() {
          $this->language->load('module/brands');
          $this->load->model('catalog/manufacturer');
          $this->load->model('tool/image');
          $this->data['heading_title'] = $this->language->get('heading_title');

          $this->data['brands'] = array();

          $results = $this->model_catalog_manufacturer->getManufacturers();
                foreach ($results as $result) {
                        $this->data['brands'][] = array(
                                'name' => $result['name'],
                                'seo_title' => $result['seo_title'],
                                'href' => $this->url->link('product/manufacturer/info','manufacturer_id=' . $result['manufacturer_id']),
                                'brand_id' => $result['manufacturer_id']
                        );
                }

          if (isset($this->request->get['manufacturer_id'])) {
            $parts = explode('_', (string)$this->request->get['manufacturer_id']);
          } else {
            $parts = array();
          }

          if (isset($parts[0])) {
            $this->data['brand_id'] = $parts[0];
          } else {
            $this->data['brand_id'] = 0;
          }
          if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/brands.tpl')) {
                 $this->template = $this->config->get('config_template') . '/template/module/brands.tpl';
          } else {
                 $this->template = 'default/template/module/brands.tpl';
          }

          $this->render();
         }
}
?>
