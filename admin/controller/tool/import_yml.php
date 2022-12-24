<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
ini_set('post_max_size','200M'); ini_set('upload_max_filesize','200M'); ini_set('max_execution_time','65536'); ini_set('max_input_time','65536'); ini_set('memory_limit','2048M'); set_time_limit(65536);
class ControllerToolImportYml extends Controller {
	private $error = array();

	private $categoryMap = array();

	private $columnsUpdate = array();

	private $skuProducts = array();

	private $flushCount = 50;

	private $file;

	private $fileXML;

	private $settings = array();

	private $productsAdded = 0;

    private $productsUpdated = 0;
    
    private $productsSkipped = 0;

    private $start_import_time = 0;

    private $import_timeout = 0;

	public function index()
	{
        $this->load->language('tool/import_yml');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addScript('view/javascript/jquery.numberMask.min.js');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->user->hasPermission('modify', 'tool/import_yml')) {

            $this->load->model('setting/setting');

            $start_import_yml_from_product_id = array('start_import_yml_from_product_id' => $this->request->post['start_import_yml_from_product_id']);
            $this->model_setting_setting->editSetting('start_import_yml_from', $start_import_yml_from_product_id);
            unset($this->request->post['start_import_yml_from_product_id']);
            
            $this->model_setting_setting->editSetting('import_yml', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_settings_success');
            $this->response->redirect($this->url->link('tool/import_yml', 'token=' . $this->session->data['token'], true));
        }

        $this->load->model('catalog/product');
        $this->load->model('catalog/attribute');

        $filter_data = array(
            'start'        => 0,
            'limit'        => 99999999999
        );

        // Получаем список предметов одежды для наценок BEGIN
        $products= $this->model_catalog_product->getProducts($filter_data);
        $data['product_attributes'] = array();


        foreach ($products as $product) {
            $product_attributes = $this->model_catalog_product->getProductAttributes($product['product_id']);
            foreach ($product_attributes as $product_attribute) {
                $attribute_info = $this->model_catalog_attribute->getAttribute($product_attribute['attribute_id']);

                if ($attribute_info) {
                    if ($attribute_info['name'] == 'Предмет одежды'){

                        $data['product_attributes'][$product_attribute['product_attribute_description'][1]['text']]['text'] = $product_attribute['product_attribute_description'][1]['text'];

                        $data['product_attributes'][$product_attribute['product_attribute_description'][1]['text']]['name'] = 'import_yml_predmet_odezhdi_' . $this->transliterate($product_attribute['product_attribute_description'][1]['text']);

                        $data['product_attributes'][$product_attribute['product_attribute_description'][1]['text']]['value'] = $this->config->get($data['product_attributes'][$product_attribute['product_attribute_description'][1]['text']]['name']);
                    }
                }
            }
        }
        // Получаем список предметов одежды для наценок END

        // Получаем список производителей BEGIN
        $this->load->model('tool/import_yml');
        $data['product_manufacturers'] = $this->model_tool_import_yml->loadManufactures();

        $this->load->model('catalog/category');
        $filter_data = array(
			'sort'        => 'name',
			'order'       => 'ASC'
		);

        $data['product_categories'] = $this->model_catalog_category->getCategories($filter_data);

        foreach ($data['product_categories'] as $key=> $product_category){
            $data['product_categories'][$key]['margin'] = $this->config->get('import_yml_category_margin_'.$product_category['category_id']);
        }

        $data['import_yml_disabled_manufacturers'] = $this->config->get('import_yml_disabled_manufacturers');
        $data['import_yml_disabled_categories'] = $this->config->get('import_yml_disabled_categories');



        // Получаем список производителей END

        if (isset($this->request->post['import_yml_url'])) {
            $data['import_yml_url'] = $this->request->post['import_yml_url'];
        } else {
            $data['import_yml_url'] = $this->config->get('import_yml_url');
        } 

        if (isset($this->request->post['import_yml_margin_type'])) {
            $data['import_yml_margin_type'] = $this->request->post['import_yml_margin_type'];
        } else {
            $data['import_yml_margin_type'] = $this->config->get('import_yml_margin_type');
        }

        if (isset($this->request->post['import_yml_main_picture'])) {
            $data['import_yml_main_picture'] = $this->request->post['import_yml_main_picture'];
        } else {
            $data['import_yml_main_picture'] = $this->config->get('import_yml_main_picture');
        }

        if (isset($this->request->post['import_yml_start_import_password'])) {
            $data['import_yml_start_import_password'] = $this->request->post['import_yml_start_import_password'];
        } else {
            $data['import_yml_start_import_password'] = $this->config->get('import_yml_start_import_password');
        }

        if (isset($this->request->post['import_yml_start_import_timeout'])) {
            $data['import_yml_start_import_timeout'] = $this->request->post['import_yml_start_import_timeout'];
        } else {
            $data['import_yml_start_import_timeout'] = $this->config->get('import_yml_start_import_timeout');
        }

        if (isset($this->request->post['start_import_yml_from_product_id'])) {
            $data['start_import_yml_from_product_id'] = $this->request->post['start_import_yml_from_product_id'];
        } else {
            $data['start_import_yml_from_product_id'] = $this->config->get('start_import_yml_from_product_id');
        }

        if (isset($this->request->post['import_yml_category_margin_krossovki'])) {
            $data['import_yml_category_margin_krossovki'] = $this->request->post['import_yml_category_margin_krossovki'];
        } else {
            $data['import_yml_category_margin_krossovki'] = $this->config->get('import_yml_category_margin_krossovki');
        }

        if (isset($this->request->post['import_yml_category_margin_odezhda'])) {
            $data['import_yml_category_margin_odezhda'] = $this->request->post['import_yml_category_margin_odezhda'];
        } else {
            $data['import_yml_category_margin_odezhda'] = $this->config->get('import_yml_category_margin_odezhda');
        }

        if (isset($this->request->post['import_yml_category_margin_aksessuari'])) {
            $data['import_yml_category_margin_aksessuari'] = $this->request->post['import_yml_category_margin_aksessuari'];
        } else {
            $data['import_yml_category_margin_aksessuari'] = $this->config->get('import_yml_category_margin_aksessuari');
        }

        if (isset($this->request->post['import_yml_product_names_margin'])) {
            $data['import_yml_product_names_margin'] = json_decode(html_entity_decode($this->request->post['import_yml_product_names_margin']), true);
            $data['import_yml_product_names_margin_string'] = $this->request->post['import_yml_product_names_margin'];
        } else {
            $data['import_yml_product_names_margin'] = json_decode(html_entity_decode($this->config->get('import_yml_product_names_margin')), true);
            $data['import_yml_product_names_margin_string'] = $this->config->get('import_yml_product_names_margin');
        }


        if (isset($this->request->post['import_yml_product_skus_margin'])) {
            $data['import_yml_product_skus_margin'] = json_decode(html_entity_decode($this->request->post['import_yml_product_skus_margin']), true);
            $data['import_yml_product_skus_margin_string'] = $this->request->post['import_yml_product_skus_margin'];
        } else {
            $data['import_yml_product_skus_margin'] = json_decode(html_entity_decode($this->config->get('import_yml_product_skus_margin')), true);
            $data['import_yml_product_skus_margin_string'] = $this->config->get('import_yml_product_skus_margin');
        }

        if (isset($this->request->post['import_yml_disabled_product_name'])) {
            $data['import_yml_disabled_product_name'] = json_decode(html_entity_decode($this->request->post['import_yml_disabled_product_name']), true);
            $data['import_yml_disabled_product_name_string'] = $this->request->post['import_yml_disabled_product_name'];
        } else {
            $data['import_yml_disabled_product_name'] = json_decode(html_entity_decode($this->config->get('import_yml_disabled_product_name')), true);
            $data['import_yml_disabled_product_name_string'] = $this->config->get('import_yml_disabled_product_name');
        }

        if (isset($this->request->post['import_yml_disabled_product_sku'])) {
            $data['import_yml_disabled_product_sku'] = json_decode(html_entity_decode($this->request->post['import_yml_disabled_product_sku']), true);
            $data['import_yml_disabled_product_sku_string'] = $this->request->post['import_yml_disabled_product_sku'];
        } else {
            $data['import_yml_disabled_product_sku'] = json_decode(html_entity_decode($this->config->get('import_yml_disabled_product_sku')), true);
            $data['import_yml_disabled_product_sku_string'] = $this->config->get('import_yml_disabled_product_sku');
        }



        if (isset($this->request->post['import_yml_meta_keyword_category'])) {
            $data['import_yml_meta_keyword_category'] = $this->request->post['import_yml_meta_keyword_category'];
        } else {
            $data['import_yml_meta_keyword_category'] = $this->config->get('import_yml_meta_keyword_category');
        }

        if (isset($this->request->post['import_yml_meta_description_category'])) {
            $data['import_yml_meta_description_category'] = $this->request->post['import_yml_meta_description_category'];
        } else {
            $data['import_yml_meta_description_category'] = $this->config->get('import_yml_meta_description_category');
        }

        if (isset($this->request->post['import_yml_meta_title_category'])) {
            $data['import_yml_meta_title_category'] = $this->request->post['import_yml_meta_title_category'];
        } else {
            $data['import_yml_meta_title_category'] = $this->config->get('import_yml_meta_title_category');
        }

        if (isset($this->request->post['import_yml_meta_h1_category'])) {
            $data['import_yml_meta_h1_category'] = $this->request->post['import_yml_meta_h1_category'];
        } else {
            $data['import_yml_meta_h1_category'] = $this->config->get('import_yml_meta_h1_category');
        }



        if (isset($this->request->post['import_yml_meta_keyword_product'])) {
            $data['import_yml_meta_keyword_product'] = $this->request->post['import_yml_meta_keyword_product'];
        } else {
            $data['import_yml_meta_keyword_product'] = $this->config->get('import_yml_meta_keyword_product');
        }

        if (isset($this->request->post['import_yml_meta_description_product'])) {
            $data['import_yml_meta_description_product'] = $this->request->post['import_yml_meta_description_product'];
        } else {
            $data['import_yml_meta_description_product'] = $this->config->get('import_yml_meta_description_product');
        }

        if (isset($this->request->post['import_yml_meta_title_product'])) {
            $data['import_yml_meta_title_product'] = $this->request->post['import_yml_meta_title_product'];
        } else {
            $data['import_yml_meta_title_product'] = $this->config->get('import_yml_meta_title_product');
        }

        if (isset($this->request->post['import_yml_meta_h1_product'])) {
            $data['import_yml_meta_h1_product'] = $this->request->post['import_yml_meta_h1_product'];
        } else {
            $data['import_yml_meta_h1_product'] = $this->config->get('import_yml_meta_h1_product');
        }



        if (isset($this->request->post['import_yml_meta_keyword_brand'])) {
            $data['import_yml_meta_keyword_brand'] = $this->request->post['import_yml_meta_keyword_brand'];
        } else {
            $data['import_yml_meta_keyword_brand'] = $this->config->get('import_yml_meta_keyword_brand');
        }

        if (isset($this->request->post['import_yml_meta_description_brand'])) {
            $data['import_yml_meta_description_brand'] = $this->request->post['import_yml_meta_description_brand'];
        } else {
            $data['import_yml_meta_description_brand'] = $this->config->get('import_yml_meta_description_brand');
        }

        if (isset($this->request->post['import_yml_meta_title_brand'])) {
            $data['import_yml_meta_title_brand'] = $this->request->post['import_yml_meta_title_brand'];
        } else {
            $data['import_yml_meta_title_brand'] = $this->config->get('import_yml_meta_title_brand');
        }

        if (isset($this->request->post['import_yml_meta_h1_brand'])) {
            $data['import_yml_meta_h1_brand'] = $this->request->post['import_yml_meta_h1_brand'];
        } else {
            $data['import_yml_meta_h1_brand'] = $this->config->get('import_yml_meta_h1_brand');
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['entry_save_settings'] = $this->language->get('entry_save_settings');
        $data['button_import'] = $this->language->get('button_import');


        $data['text_seo_section'] = $this->language->get('text_seo_section');
        $data['text_seo_description'] = $this->language->get('text_seo_description');

        $data['entry_import_yml_meta_keyword_category'] = $this->language->get('entry_import_yml_meta_keyword_category');
        $data['entry_import_yml_meta_description_category'] = $this->language->get('entry_import_yml_meta_description_category');
        $data['entry_import_yml_meta_title_category'] = $this->language->get('entry_import_yml_meta_title_category');
        $data['entry_import_yml_meta_h1_category'] = $this->language->get('entry_import_yml_meta_h1_category');

        $data['entry_import_yml_meta_keyword_product'] = $this->language->get('entry_import_yml_meta_keyword_product');
        $data['entry_import_yml_meta_description_product'] = $this->language->get('entry_import_yml_meta_description_product');
        $data['entry_import_yml_meta_title_product'] = $this->language->get('entry_import_yml_meta_title_product');
        $data['entry_import_yml_meta_h1_product'] = $this->language->get('entry_import_yml_meta_h1_product');

        $data['entry_import_yml_meta_keyword_brand'] = $this->language->get('entry_import_yml_meta_keyword_brand');
        $data['entry_import_yml_meta_description_brand'] = $this->language->get('entry_import_yml_meta_description_brand');
        $data['entry_import_yml_meta_title_brand'] = $this->language->get('entry_import_yml_meta_title_brand');
        $data['entry_import_yml_meta_h1_brand'] = $this->language->get('entry_import_yml_meta_h1_brand');

        $data['entry_url'] = $this->language->get('entry_url');
        $data['entry_start_from_product'] = $this->language->get('entry_start_from_product');
        $data['entry_margin_type'] = $this->language->get('entry_margin_type');
        $data['entry_main_picture'] = $this->language->get('entry_main_picture');
        $data['entry_start_import_password'] = $this->language->get('entry_start_import_password');
        $data['entry_start_import_timeout'] = $this->language->get('entry_start_import_timeout');

        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];

            unset($this->session->data['error']);
        } elseif (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('tool/import_yml', 'token=' . $this->session->data['token'], true)
        );

        $data['save'] = $this->url->link('tool/import_yml', 'token=' . $this->session->data['token'], true);
        $data['import'] = $this->url->link('tool/import_yml/import', 'token=' . $this->session->data['token'], true);


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('tool/import_yml', $data));
	}

    public function importcron(){
        if (!isset($_GET['pass']) || $_GET['pass'] != $this->config->get('import_yml_start_import_password')) {
            return;
        } else {
            $this->import();
        }
    }

	public function import() {
        $this->start_import_time = time();
        $this->import_timeout = $this->config->get('import_yml_start_import_timeout');

        $url = $this->config->get('import_yml_url');
        
        if (!empty($url)) {
            $this->load->model('tool/import_yml');
            $this->load->model('catalog/product');
            $this->load->model('catalog/manufacturer');
            $this->load->model('catalog/category');
            $this->load->model('catalog/attribute');
            $this->load->model('catalog/option');
            $this->load->model('catalog/attribute_group');
            $this->load->model('localisation/language');            
            $this->parseFile($url);
        } else {
            $this->load->language('tool/import_yml');
            $this->session->data['error'] = $this->language->get('error_yml_url');
            $this->response->redirect($this->url->link('tool/import_yml', 'token=' . $this->session->data['token'], true));
        }
    }

    private function parseFile($file)
    {
        $logFile = fopen(DIR_LOGS . "import.log", 'w') or die("не удалось создать файл");
        fwrite($logFile, 'Import Begin: ' . date("Y-m-d H:i:s") . "\r\n");
        fclose($logFile);

        set_time_limit(0);
        $xmlstr = file_get_contents($file);
        $xml = new SimpleXMLElement($xmlstr);

        $this->fileXML = $xml;

        // $this->model_tool_import_yml->deleteCategories();
        // $this->model_tool_import_yml->deleteProducts();
        // $this->model_tool_import_yml->deleteManufactures();
        // $this->model_tool_import_yml->deleteAttributes();
        // $this->model_tool_import_yml->deleteAttributeGroups();


        /*$result = $this->db->query('SELECT product_id, sku FROM `' . DB_PREFIX . 'product` ');
        if (!empty($result->rows)) {
            foreach ($result->rows as $row) {
                $this->skuProducts[ $row['sku'] ] = $row['product_id'];
            }
        }*/

        // Prepare big file upload feature
        /*if (empty($this->settings['import_yml_file']['file_hash'])
            || $this->settings['import_yml_file']['file_hash'] != md5($this->file)
        ) {
            $this->model_setting_setting->editSetting('import_yml_file', array(
                'file_hash' => md5($this->file),
                'file_name' => DIR_DOWNLOAD . 'import.yml',
                'loaded' => 0
            ));

            $this->settings['import_yml_file'] = $this->model_setting_setting->getSetting('import_yml_file');
        }*/

        $this->addCategories($xml->shop->categories);
        $this->addProducts($xml->shop->offers);

    }

    private function addCategories($categories)
    {

        $logFile = fopen(DIR_LOGS . "import.log", 'a') or die("не удалось создать файл");
        fwrite($logFile, 'Add categories Begin: ' . date("Y-m-d H:i:s") . "\r\n");
        fclose($logFile);

        $import_yml_meta_keyword_category = $this->config->get('import_yml_meta_keyword_category');                    
        $import_yml_meta_description_category = $this->config->get('import_yml_meta_description_category');
        $import_yml_meta_title_category =  $this->config->get('import_yml_meta_title_category');        
        $import_yml_meta_h1_category =  $this->config->get('import_yml_meta_h1_category');

        // $logFile = fopen(DIR_LOGS . "import.log", 'a') or die("не удалось создать файл");
        // fwrite($logFile, "Исходные шаблоны категорий: \r\n meta_keyword_category: " . $import_yml_meta_keyword_category . "\r\n meta_description_category: " . $import_yml_meta_description_category . "\r\n meta_title_category: " . $import_yml_meta_title_category . "\r\n meta_h1_category: " . $import_yml_meta_h1_category . "\r\n");
        // fclose($logFile);

        $config_name = $this->config->get('config_name');

        $this->categoryMap = array(
            0 => 0
        );

        $categoriesList = array();

        foreach ($categories->category as $category) {

            $categoriesList[ (string)$category['id'] ] = array(
                'id' => (int)$category['id'],
                'parent_id'   => (int)$category['parentId'],
                'name'        => trim((string)$category)
            );
        }



        

        $filter_data = array(
			'sort'        => 'name',
			'order'       => 'ASC'
		);

        $allCategoriesOnSite = $this->model_catalog_category->getCategories($filter_data);

        // echo  '<pre>';
        // print_r($allCategoriesOnSite);
        // echo  '</pre><br/>';
        
        foreach ($allCategoriesOnSite as $category) {
            if (!array_key_exists($category['category_id'], $categoriesList)) {
                $this->db->query( "UPDATE `".DB_PREFIX."category` SET `status`='0' WHERE `category_id`='" . (string)$category['category_id'] . "'" );
            }
        }

        // Compare categories level by level and create new one, if it doesn't exist
        while (count($categoriesList) > 0) {
            foreach ($categoriesList as $source_category_id => $item) {
                if (array_key_exists((int)$item['parent_id'], $this->categoryMap)) {
                    $category = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'category` INNER JOIN `' . DB_PREFIX . 'category_description` ON `' . DB_PREFIX . 'category_description`.category_id = `' . DB_PREFIX . 'category`.category_id WHERE parent_id = ' . (int)$this->categoryMap[$item['parent_id']] . ' AND (`' . DB_PREFIX . 'category_description`.name LIKE "' . $this->db->escape($item['name']) . '" OR `' . DB_PREFIX . 'category`.category_id = "' . $this->db->escape($item['id']) . '")');

                    
                    $CatTitle = ($item['name'] == "Акция")? "Акция" : $item['name'];

                    if ($item['name'] == "Кроссовки") {
                        $sort_order = 1;
                    } else if ($item['name'] == "Одежда"){
                        $sort_order = 2;
                    } else if ($item['name'] == "Аксессуары"){
                        $sort_order = 3;
                    } else if ($item['name'] == "Мужские") {
                        $sort_order = 4;
                    } else if ($item['name'] == "Женские"){
                        $sort_order = 5;
                    } else if ($item['name'] == "Детские"){
                        $sort_order = 6;
                    } else if ($item['name'] == "Мужская") {
                        $sort_order = 7;
                    } else if ($item['name'] == "Женская"){
                        $sort_order = 8;
                    } else if ($item['name'] == "Детская"){
                        $sort_order = 9;
                    } else {
                        $sort_order = 9999999;
                    }



                    if ($item['id'] == "10013415" || $item['id'] == "11970" || $item['id'] == "7609" || $item['parent_id'] == "7609"|| $item['id'] == "7607" || $item['parent_id'] == "7607" ) { // Этих уже нет
                        $cat_status = 0;
                    }

                    if ((!$item['parent_id']) && ($item['name'] != "Кроссовки" && $item['name'] != "Одежда" && $item['name'] != "Аксессуары")){
                        $cat_status = 0;
                    }

                    if ( 
                        $item['parent_id'] == '1789' || //Кроссовки ->
                        $item['id'] == "1789" || //Кроссовки
                        $item['parent_id'] == '4' || //Одежда ->
                        $item['id'] == "4" || //Одежда ->
                        $item['parent_id'] == '3' || //Аксессуары ->
                        $item['id'] == "3" || //Аксессуары
                        $item['parent_id'] == '1' || //Кроссовки -> Мужские
                        $item['parent_id'] == '97' ||  //Кроссовки -> Женские
                        $item['parent_id'] == '1523' ||  //Кроссовки -> Детские
                        $item['parent_id'] == '1597' || //Одежда -> Мужская
                        $item['parent_id'] == '1596' || //Одежда -> Женская
                        $item['parent_id'] == '10622' //Одежда -> Детская
                    ) {
                        $cat_status = 1;
                    } else {
                        $cat_status = 0;
                    }


                    if ($item['id'] == "3") {
                        $column = '4';
                    } else {
                        $column = '3';
                    }


                    $meta_product_name = '';
                    $meta_brand_name = '';
                    $meta_category_name = $item['name'];                    
                    $meta_shop_name = $config_name;
                    $meta_parent_categories_names = '';

                    // generating transliterated category keyword BEGIN
                    $category_keyword = '';                    
                    if ((int)$item['parent_id'] !== 0){
                        $parent_category1 = $this->model_catalog_category->getCategory((int)$item['parent_id']);

                        if (isset($parent_category1['parent_id']) && $parent_category1['parent_id'] !== 0) {
                            $parent_category2 = $this->model_catalog_category->getCategory($parent_category1['parent_id']);

                            if (isset($parent_category2['parent_id']) && $parent_category2['parent_id'] !== 0) {
                                $category_keyword = $this->transliterate(str_replace('"', "", $parent_category2['name'])) . "-" . $this->transliterate(str_replace('"', "", $parent_category1['name'])) . "-" . $this->transliterate(str_replace('"', "", $item['name']));

                                $meta_parent_categories_names = $parent_category2['name'] . ' ' . $parent_category1['name'];

                            } else {
                                $category_keyword = $this->transliterate(str_replace('"', "", $parent_category1['name'])) . "-" . $this->transliterate(str_replace('"', "", $item['name']));

                                $meta_parent_categories_names = $parent_category1['name'];
                            }
                        } else {
                            $this->transliterate(str_replace('"', "", $parent_category1['name'])) . "-" . $this->transliterate(str_replace('"', "", $item['name']));

                            $meta_parent_categories_names = $parent_category1['name'];
                        }
                    } else {
                        $category_keyword = $this->transliterate(str_replace('"', "", $item['name']));
                    }
                    
                    // generating transliterated category keyword END

                    //skipping disabled categories
                    $import_yml_disabled_categories = $this->config->get('import_yml_disabled_categories');
                    if (is_array($import_yml_disabled_categories)) {
                        foreach ( $import_yml_disabled_categories as $disabledCategory){
                            if ($item['id'] == $disabledCategory || $item['parent_id'] == $disabledCategory ){
                                $cat_status = 0;
                            }
                        }
                    }  

                    // $logFile = fopen(DIR_LOGS . "import.log", 'a') or die("не удалось создать файл");
                    // fwrite($logFile, 'Переменные категорий:' . "\r\n shop_name: " .  $meta_shop_name . "\r\n parent_categories_names: " . $meta_parent_categories_names . "\r\n category_name: " . $meta_category_name . "\r\n brand_name: " . $meta_brand_name . "\r\n product_name: " . $meta_product_name . "\r\n");
                    // fclose($logFile);

                    
                    $meta_import_yml_meta_keyword_category = str_replace("{product_name}", $meta_product_name, $import_yml_meta_keyword_category);
                    $meta_import_yml_meta_keyword_category = str_replace("{brand_name}", $meta_brand_name, $meta_import_yml_meta_keyword_category);
                    $meta_import_yml_meta_keyword_category = str_replace("{category_name}", $meta_category_name, $meta_import_yml_meta_keyword_category);
                    $meta_import_yml_meta_keyword_category = str_replace("{parent_categories_names}", $meta_parent_categories_names, $meta_import_yml_meta_keyword_category);
                    $meta_import_yml_meta_keyword_category = str_replace("{shop_name}", $meta_shop_name, $meta_import_yml_meta_keyword_category);
                    $meta_import_yml_meta_keyword_category = str_replace("  ", " ", $meta_import_yml_meta_keyword_category);
                    
                    $meta_import_yml_meta_description_category = str_replace("{product_name}", $meta_product_name, $import_yml_meta_description_category);
                    $meta_import_yml_meta_description_category = str_replace("{brand_name}", $meta_brand_name, $meta_import_yml_meta_description_category);
                    $meta_import_yml_meta_description_category = str_replace("{category_name}", $meta_category_name, $meta_import_yml_meta_description_category);
                    $meta_import_yml_meta_description_category = str_replace("{parent_categories_names}", $meta_parent_categories_names, $meta_import_yml_meta_description_category);
                    $meta_import_yml_meta_description_category = str_replace("{shop_name}", $meta_shop_name, $meta_import_yml_meta_description_category);
                    $meta_import_yml_meta_description_category = str_replace("  ", " ", $meta_import_yml_meta_description_category);
                    
                    $meta_import_yml_meta_title_category = str_replace("{product_name}", $meta_product_name, $import_yml_meta_title_category);
                    $meta_import_yml_meta_title_category = str_replace("{brand_name}", $meta_brand_name, $meta_import_yml_meta_title_category);
                    $meta_import_yml_meta_title_category = str_replace("{category_name}", $meta_category_name, $meta_import_yml_meta_title_category);
                    $meta_import_yml_meta_title_category = str_replace("{parent_categories_names}", $meta_parent_categories_names, $meta_import_yml_meta_title_category);
                    $meta_import_yml_meta_title_category = str_replace("{shop_name}", $meta_shop_name, $meta_import_yml_meta_title_category);
                    $meta_import_yml_meta_title_category = str_replace("  ", " ", $meta_import_yml_meta_title_category);
                    
                    $meta_import_yml_meta_h1_category = str_replace("{product_name}", $meta_product_name, $import_yml_meta_h1_category);
                    $meta_import_yml_meta_h1_category = str_replace("{brand_name}", $meta_brand_name, $meta_import_yml_meta_h1_category);
                    $meta_import_yml_meta_h1_category = str_replace("{category_name}", $meta_category_name, $meta_import_yml_meta_h1_category);
                    $meta_import_yml_meta_h1_category = str_replace("{parent_categories_names}", $meta_parent_categories_names, $meta_import_yml_meta_h1_category);
                    $meta_import_yml_meta_h1_category = str_replace("{shop_name}", $meta_shop_name, $meta_import_yml_meta_h1_category);
                    $meta_import_yml_meta_h1_category = str_replace("  ", " ", $meta_import_yml_meta_h1_category);


                    // $logFile = fopen(DIR_LOGS . "import.log", 'a') or die("не удалось создать файл");
                    // fwrite($logFile, "Шаблоны категорий: \r\n meta_keyword_category: " . $meta_import_yml_meta_keyword_category . "\r\n meta_description_category: " . $meta_import_yml_meta_description_category . "\r\n meta_title_category: " . $meta_import_yml_meta_title_category . "\r\n meta_h1_category: " . $meta_import_yml_meta_h1_category . "\r\n");
                    // fclose($logFile);
                    

                    $category_data = array (
                        'category_id' => (int)$item['id'],
                        'sort_order' => $sort_order,
                        'parent_id' => $this->categoryMap[ (int)$item['parent_id'] ],
                        'top' => 0,
                        'status' => $cat_status,
                        'column' => $column,
                        'category_description' => array (
                            1 => array(
                                'name' => $item['name'],
                                // 'meta_keyword' => 'Купить ' . $item['name'] . ' в интернет-магазине ' . $config_name . ' с доставкой',
                                'meta_keyword' => $meta_import_yml_meta_keyword_category,
                                // 'meta_description' => 'Купить ' . $item['name'] . ' в интернет-магазине ' . $config_name . ' с доставкой',
                                'meta_description' => $meta_import_yml_meta_description_category,
                                'description' => '',
                                // 'meta_title' => 'Купить ' . $CatTitle . ' в интернет-магазине ' . $config_name . ' с доставкой',
                                'meta_title' => $meta_import_yml_meta_title_category,
                                // 'meta_h1' => $item['name']
                                'meta_h1' => $meta_import_yml_meta_h1_category
                            )
                        ),
                        'keyword' => $category_keyword,
                        'category_store' => array (
                            0
                        ),
                    );

                    if ($category_data['parent_id'] == 0) {
                        $category_data['top'] = 1;
                    }

                    if ($category->row) {
                        $this->model_catalog_category->editCategory($category->row['category_id'], $category_data);
                        $this->categoryMap[(int)$source_category_id] = $category->row['category_id'];
                    } else {
                        $this->categoryMap[(int)$source_category_id] = $this->model_catalog_category->addCategory($category_data);
                    }
                    unset($categoriesList[$source_category_id]);
                }
            }
        }
    }

    private function addProducts($offers)
    {

        $this->load->model('setting/setting');
        $logFile = fopen(DIR_LOGS . "import.log", 'a') or die("не удалось создать файл");
        fwrite($logFile, 'Add products Begin: ' . date("Y-m-d H:i:s") . "\r\n");


        $import_yml_meta_keyword_product = $this->config->get('import_yml_meta_keyword_product');                    
        $import_yml_meta_description_product = $this->config->get('import_yml_meta_description_product');
        $import_yml_meta_title_product =  $this->config->get('import_yml_meta_title_product');        
        $import_yml_meta_h1_product =  $this->config->get('import_yml_meta_h1_product');


        $import_yml_meta_keyword_brand = $this->config->get('import_yml_meta_keyword_brand');                    
        $import_yml_meta_description_brand = $this->config->get('import_yml_meta_description_brand');
        $import_yml_meta_title_brand =  $this->config->get('import_yml_meta_title_brand');        
        $import_yml_meta_h1_brand =  $this->config->get('import_yml_meta_h1_brand');

        // $logFile = fopen(DIR_LOGS . "import.log", 'a') or die("не удалось создать файл");
        // fwrite($logFile, "Исходные шаблоны товаров: \r\n meta_keyword_product: " . $import_yml_meta_keyword_product . "\r\n meta_description_product: " . $import_yml_meta_description_product . "\r\n meta_title_product: " . $import_yml_meta_title_product . "\r\n meta_h1_product: " . $import_yml_meta_h1_product . "\r\n");
        // fclose($logFile);

        // $logFile = fopen(DIR_LOGS . "import.log", 'a') or die("не удалось создать файл");
        // fwrite($logFile, "Исходные шаблоны производителей: \r\n meta_keyword_brand: " . $import_yml_meta_keyword_brand . "\r\n meta_description_brand: " . $import_yml_meta_description_brand . "\r\n meta_title_brand: " . $import_yml_meta_title_brand . "\r\n meta_h1_brand: " . $import_yml_meta_h1_brand . "\r\n");
        // fclose($logFile);

        // Получаем все товары и отключаем отсутствующие в файле BEGIN
         $filter_data = array(
            'start'        => 0,
            'limit'        => 99999999999,
            'filter_status' => '1',
            'sort'            => 'p.sort_order',
			'order'           => 'ASC',
        );

        
        $products = $this->model_catalog_product->getProducts($filter_data);
        $productsNotInFile = 0;
        $offersList = array();
        $n = count($offers->offer);
        fwrite($logFile, 'Offers in File: ' . $n . "\r\n" . "Products on the site: " . count($products). "\r\n");

        for ($i = 0; $i < $n; $i++) {
            $offer = $offers->offer[ $i ];
            array_push($offersList, (int)$offer['id']);
        }

        foreach ($products as $product) {

            $offerInStock = false;

            foreach ($offersList as $key => $offer) {

                if ($product['product_id'] == $offer) {
                    $offerInStock = true;
                    array_splice($offersList, $key, 1);
                    break;
                } else if ($product['product_id'] <= $offer) {
                    break;
                }
            }

            if (!$offerInStock) {
                $productsNotInFile++;
                $this->db->query( "UPDATE ".DB_PREFIX."product SET status='0', quantity = '0' WHERE `product_id`='" . $product['product_id'] . "'" );
                fwrite($logFile, "Disabled product which is not in file: " . $product['product_id'] . "\r\n");
            }
        }

        fclose($logFile);
        // Получаем все товары и отключаем отсутствующие в файле END

        $product_names_margin = json_decode(html_entity_decode($this->config->get('import_yml_product_names_margin')), true);
        $product_skus_margin = json_decode(html_entity_decode($this->config->get('import_yml_product_skus_margin')), true);
        $disabled_product_names = json_decode(html_entity_decode($this->config->get('import_yml_disabled_product_name')), true);
        $disabled_product_sku = json_decode(html_entity_decode($this->config->get('import_yml_disabled_product_sku')), true);


        $import_yml_category_margin_krossovki = $this->config->get('import_yml_category_margin_krossovki');
        $import_yml_category_margin_odezhda = $this->config->get('import_yml_category_margin_odezhda');
        $import_yml_category_margin_aksessuari = $this->config->get('import_yml_category_margin_aksessuari');

        $import_yml_margin_type = $this->config->get('import_yml_margin_type');

        $import_yml_main_picture = $this->config->get('import_yml_main_picture');

        $config_name = $this->config->get('config_name');

        // get first attribute group

        $res = $this->model_tool_import_yml->checkAttributeGroupsExists();
        if (!$res) {
            $attr_group_data = array (
                'sort_order' => 0,
                'attribute_group_description' => array (
                    1 => array (
                        'name' => 'Характеристики товара',
                    ),
                )
            );

            $attrGroupId = $this->model_catalog_attribute_group->addAttributeGroup($attr_group_data);
        } else {
            $attrGroupId = (int)$res['attribute_group_id'];
        }



        if (!is_dir(DIR_IMAGE . 'catalog/products')) {
            mkdir(DIR_IMAGE . 'catalog/products');
        }
        //получаем существующих Производителей
        $vendorMap = $this->model_tool_import_yml->loadManufactures();

        //получаем существующие Атрибуты
        $attrMap = $this->model_tool_import_yml->loadAttributes();

        //получаем существующие Опции
        $sizeOptionId = $this->db->query("SELECT option_id FROM `". DB_PREFIX ."option_description` WHERE name = 'Размер'");
        $optionsMap = array();

        if ($sizeOptionId->row['option_id']) {
            $optionId = $sizeOptionId->row['option_id'];
            $optionsMapData = $this->model_catalog_option->getOptionValues($sizeOptionId->row['option_id']);
            foreach ($optionsMapData as $option) {
                $optionsMap[$option['name']] = $option;
            }
        } else {
            $option_data = array (
                'sort_order' => '1',
                'type'       => 'radio',
                'option_description' => array (
                        1 => array (
                        'name' => 'Размер',
                    )
                ),
            );

            $optionId = $this->model_catalog_option->addOption($option_data);
        }
        

        $start_import_yml_from_product_id = $this->config->get('start_import_yml_from_product_id');
        if (!$start_import_yml_from_product_id) {
            $start_import_yml_from_product_id == 1;
        } else {
            $start_import_yml_from_product_id = (int)$start_import_yml_from_product_id;
        }
        $start = 0;

        //Here is start adding products
        $n = count($offers->offer);
        $flushCounter = $this->flushCount;

        //foreach ($offers->offer as $offer) {
        for ($i = $start; $i < $n; $i++) {

            if ($this->import_timeout) {
                if ((time() - $this->start_import_time ) > ( (60 * (int)$this->import_timeout) - 30) ) {
                    $logFile = fopen(DIR_LOGS . "import.log", 'a') or die("не удалось создать файл");
                    fwrite($logFile, "Import stopped by timeout (". (string)(time() - $this->start_import_time) .  " seconds after starting)\r\n");
                    fclose($logFile);
                    header('location:'.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
                    exit;
                }
            }


            $offer = $offers->offer[ $i ];

            if ((string)$offer->vendor == '') {
                $offer->vendor = 'Не указан';
            }

            if ($start_import_yml_from_product_id) {
                if ( (int)$offer['id'] < $start_import_yml_from_product_id ) {
                    continue;
                }
            }
            

            $logFile = fopen(DIR_LOGS . "import.log", 'a') or die("не удалось создать файл");
            fwrite($logFile, (string)$offer['id']  . "\r\n");
            fclose($logFile);

            //skipping disabled manufacturers
            $import_yml_disabled_manufacturers = $this->config->get('import_yml_disabled_manufacturers');
            if (is_array($import_yml_disabled_manufacturers)) {
                if ( isset($vendorMap[(string)$offer->vendor]) && in_array($vendorMap[(string)$offer->vendor], $import_yml_disabled_manufacturers)) {
                    $offer['available'] = 'false';
                    // continue;
                }
            }

            //skipping disabled categories
            $productCategoryIsDisabled = false;
            $import_yml_disabled_categories = $this->config->get('import_yml_disabled_categories');

            if (is_array($import_yml_disabled_categories)) {

                foreach ( $import_yml_disabled_categories as $disabledCategory){
                    if ($offer->categoryId == $disabledCategory) {
                        $offer['available'] = 'false';
                        $productCategoryIsDisabled = true;
                    } else {

                        $this_product_categories = $this->model_tool_import_yml->getCategoryParents((int)$offer->categoryId);
                        foreach ($this_product_categories as $this_cur_category) {
                            if ($this_cur_category['path_id'] == $disabledCategory){
                                $offer['available'] = 'false';
                                $productCategoryIsDisabled = true;
                            }
                        }
                    }
                }
            }

            //skipping disabled names
            if (is_array($disabled_product_names) || is_object($disabled_product_names)) {
                foreach ($disabled_product_names as $disabled_product_name){
                    if ( mb_stripos((string)$offer->name, $disabled_product_name) !== false) {
                        $offer['available'] = 'false';
                    }
                }
            }

            //skipping disabled sku
            if (is_array($disabled_product_sku) || is_object($disabled_product_sku)) {
                foreach ($disabled_product_sku as $disabled_sku){
                    if ( (string)$offer->id == $disabled_sku || $offer['id'] == $disabled_sku) {
                        $offer['available'] = 'false';
                    }
                }
            }

            if ($offer['available'] == 'false') {
                $this->productsSkipped++;
                // continue;
            }

        //
        if ($productCategoryIsDisabled == false) {
            if ( $offer->categoryId != '1163' && $offer->categoryId != '1163' && $offer->categoryId != '1163' && $offer->categoryId != '1163') {
                $this->db->query( "UPDATE `".DB_PREFIX."category` SET `status`='1' WHERE `category_id`='" . $offer->categoryId . "'" );
            }
        }


            $product_images = array();

            $dir_name = 'catalog/products/';
            $dir_name .= (isset($offer->id))? (string)$offer->id : (string)$offer['id'];
            $dir_name .= '/';

            if (!is_dir(DIR_IMAGE . $dir_name)) {
                mkdir(DIR_IMAGE . $dir_name, 0777, true);
            }
            $pict_number = 0;
            $productName = (string)$offer->name;

            // загрузка фотографий по ссылкам из файла
            foreach ($offer->picture as $picture) {

                $pict_number = $pict_number+1;

                $name_translit = $this->transliterate($productName);
                if ((string)$offer->vendor == 'Не указан') {
                    $vendor_translit = 'no-brand';
                } else {
                    $vendor_translit = $this->transliterate($offer->vendor);
				    $vendor_translit = ucfirst(strtolower($vendor_translit));
                }
				

                $img_name = $vendor_translit . '-' . $name_translit;
                $img_name .= "-" . (isset($offer->id))? (string)$offer->id : (string)$offer['id'];
                $img_name .= "-" . $pict_number  . "." . substr(strrchr($picture, '.'), 1);

                if (!empty($img_name)) {                    
                    $image = $this->loadImageFromHost($picture, DIR_IMAGE . $dir_name . $img_name);
                    if ($image) {
                        $product_images[] = array('image' => $dir_name . $img_name, 'sort_order' => $pict_number);
                    }
                }
            }

            // загрузка фотографий по ссылкам из файла Конец



            $image_path_array = array_shift($product_images);
            if (is_array($image_path_array)) {
                $image_path = $image_path_array['image'];
            }

            if ($import_yml_main_picture == "2") {
                if (isset($product_images[0])) {
                    $image_path = array_shift($product_images);
                    if (is_array($image_path)) {
                        $image_path = $image_path['image'];
                    }
                    array_unshift($product_images, $image_path_array);
                }
            }
                


            if (!$productName) {
                if (isset($offer->typePrefix)) {
                    $productName = (string)$offer->typePrefix . ' ' . (string)$offer->name;
                } else {
                    $productName = (string)$offer->name;
                }
            }

            $languages = $this->model_localisation_language->getLanguages();

            // gettin meta_parent_categories_names BEGIN  
            $meta_parent_categories_names = ''; 
            $product_main_category = $this->model_catalog_category->getCategory($this->categoryMap[(int)$offer->categoryId]);    

            if ((int)$product_main_category['parent_id'] !== 0){
                $parent_category1 = $this->model_catalog_category->getCategory((int)$product_main_category['parent_id']);

                if (isset($parent_category1['parent_id']) && $parent_category1['parent_id'] !== 0) {
                    $parent_category2 = $this->model_catalog_category->getCategory($parent_category1['parent_id']);

                    if (isset($parent_category2['parent_id']) && $parent_category2['parent_id'] !== 0) {
                        $meta_parent_categories_names = $parent_category2['name'] . ' ' . $parent_category1['name'];
                    } else {
                        $meta_parent_categories_names = $parent_category1['name'];
                    }
                } else {
                    $meta_parent_categories_names = $parent_category1['name'];
                }
            }
            
            // gettin meta_parent_categories_names END


            $meta_product_name = $productName;
            $meta_brand_name = (string)$offer->vendor;
            $meta_category_name = $product_main_category['name'];                    
            $meta_shop_name = $config_name;
            $meta_parent_categories_names = $meta_parent_categories_names;

            // $logFile = fopen(DIR_LOGS . "import.log", 'a') or die("не удалось создать файл");
            // fwrite($logFile, 'Переменные товаров:' . "\r\n shop_name: " .  $meta_shop_name . "\r\n parent_categories_names: " . $meta_parent_categories_names . "\r\n category_name: " . $meta_category_name . "\r\n brand_name: " . $meta_brand_name . "\r\n product_name: " . $meta_product_name . "\r\n");
            // fclose($logFile);

            $meta_import_yml_meta_keyword_product = str_replace("{product_name}", $meta_product_name, $import_yml_meta_keyword_product);
            $meta_import_yml_meta_keyword_product = str_replace("{brand_name}", $meta_brand_name, $meta_import_yml_meta_keyword_product);
            $meta_import_yml_meta_keyword_product = str_replace("{category_name}", $meta_product_name, $meta_import_yml_meta_keyword_product);
            $meta_import_yml_meta_keyword_product = str_replace("{parent_categories_names}", $meta_parent_categories_names, $meta_import_yml_meta_keyword_product);
            $meta_import_yml_meta_keyword_product = str_replace("{shop_name}", $meta_shop_name, $meta_import_yml_meta_keyword_product);
            $meta_import_yml_meta_keyword_product = str_replace("  ", " ", $meta_import_yml_meta_keyword_product);
            
            $meta_import_yml_meta_description_product = str_replace("{product_name}", $meta_product_name, $import_yml_meta_description_product);
            $meta_import_yml_meta_description_product = str_replace("{brand_name}", $meta_brand_name, $meta_import_yml_meta_description_product);
            $meta_import_yml_meta_description_product = str_replace("{category_name}", $meta_product_name, $meta_import_yml_meta_description_product);
            $meta_import_yml_meta_description_product = str_replace("{parent_categories_names}", $meta_parent_categories_names, $meta_import_yml_meta_description_product);
            $meta_import_yml_meta_description_product = str_replace("{shop_name}", $meta_shop_name, $meta_import_yml_meta_description_product);
            $meta_import_yml_meta_description_product = str_replace("  ", " ", $meta_import_yml_meta_description_product);
            
            $meta_import_yml_meta_title_product = str_replace("{product_name}", $meta_product_name, $import_yml_meta_title_product);
            $meta_import_yml_meta_title_product = str_replace("{brand_name}", $meta_brand_name, $meta_import_yml_meta_title_product);
            $meta_import_yml_meta_title_product = str_replace("{category_name}", $meta_product_name, $meta_import_yml_meta_title_product);
            $meta_import_yml_meta_title_product = str_replace("{parent_categories_names}", $meta_parent_categories_names, $meta_import_yml_meta_title_product);
            $meta_import_yml_meta_title_product = str_replace("{shop_name}", $meta_shop_name, $meta_import_yml_meta_title_product);
            $meta_import_yml_meta_title_product = str_replace("  ", " ", $meta_import_yml_meta_title_product);
            
            $meta_import_yml_meta_h1_product = str_replace("{product_name}", $meta_product_name, $import_yml_meta_h1_product);
            $meta_import_yml_meta_h1_product = str_replace("{brand_name}", $meta_brand_name, $meta_import_yml_meta_h1_product);
            $meta_import_yml_meta_h1_product = str_replace("{category_name}", $meta_product_name, $meta_import_yml_meta_h1_product);
            $meta_import_yml_meta_h1_product = str_replace("{parent_categories_names}", $meta_parent_categories_names, $meta_import_yml_meta_h1_product);
            $meta_import_yml_meta_h1_product = str_replace("{shop_name}", $meta_shop_name, $meta_import_yml_meta_h1_product);
            $meta_import_yml_meta_h1_product = str_replace("  ", " ", $meta_import_yml_meta_h1_product);

            foreach ($languages as $language) {
                $product_description[ $language['language_id'] ] = array (
                    'name' => $productName,
                    // 'meta_keyword' => 'Купить ' . $productName . ' в интернет-магазине ' . $config_name . ' с доставкой',
                    'meta_keyword' => $meta_import_yml_meta_keyword_product,
                    // 'meta_description' => 'Купить ' . $productName . ' в интернет-магазине ' . $config_name . ' с доставкой',
                    'meta_description' => $meta_import_yml_meta_description_product,
                    'description' => '<p>' .(string)$offer->description,
                    'tag' => $productName,
                    // 'meta_title' => 'Купить ' . $productName . ' в интернет-магазине ' . $config_name . ' с доставкой',
                    'meta_title' => $meta_import_yml_meta_title_product,
                    // 'meta_h1' => $productName,
                    'meta_h1' => $meta_import_yml_meta_h1_product,
                );
            }

            //Рассчитываем цену
            $haveSpecial = false;
            if (isset($offer->param)) {
                $params = $offer->param;
                foreach ($params as $param) {
                    if ( (string)$param['name'] == "Скидка" ) {
                        $haveSpecial = (int)$param;
                    }
                }
            }
            
            if ($offer->categoryId == "1789"){ //кроссовки
                if ($import_yml_margin_type == 'percents') {
                    $New_Price = ceil( ((float)$offer->price + ( ((float)$offer->price / 100) * $import_yml_category_margin_krossovki))  /100)*100-10;
                } else {
                    $New_Price = (float)$offer->price + $import_yml_category_margin_krossovki;
                }
                
                
            } else if ($offer->categoryId == "4"){ //одежда
                if ($import_yml_margin_type == 'percents') {
                    $New_Price = ceil( ((float)$offer->price + ( ((float)$offer->price / 100) * $import_yml_category_margin_odezhda))  /100)*100-10;
                } else {
                    $New_Price = (float)$offer->price + $import_yml_category_margin_odezhda;
                }
            } else if ($offer->categoryId == "3"){ //аксессуары
                if ($import_yml_margin_type == 'percents') {
                    $New_Price = ceil( ((float)$offer->price + ( ((float)$offer->price / 100) * $import_yml_category_margin_aksessuari))  /100)*100-10;
                } else {
                    $New_Price = (float)$offer->price + $import_yml_category_margin_aksessuari;
                }
            } else {
                $New_Price = (float)$offer->price;
            }
            //Рассчитываем цену Конец

            $this_product_categories = $this->model_tool_import_yml->getCategoryParents((int)$offer->categoryId);
            $this_i=0;
            unset($this_product_category);

            foreach ($this_product_categories as $this_cur_category) {
                $this_product_category[$this_i] = $this_cur_category['path_id'];
                $this_i=$this_i+1;
                //Рассчитываем цену
                if ($this_cur_category['path_id'] == "1789"){ //кроссовки
                    if ($import_yml_margin_type == 'percents') {
                        $New_Price = ceil( ((float)$offer->price + ( ((float)$offer->price / 100) * $import_yml_category_margin_krossovki))  /100)*100-10;
                    } else {
                        $New_Price = (float)$offer->price + $import_yml_category_margin_krossovki;
                    }
                }

                if ($this_cur_category['path_id'] == "4"){ //одежда
                    if ($import_yml_margin_type == 'percents') {
                        $New_Price = ceil( ((float)$offer->price + ( ((float)$offer->price / 100) * $import_yml_category_margin_odezhda))  /100)*100-10;
                    } else {
                        $New_Price = (float)$offer->price + $import_yml_category_margin_odezhda;
                    }
                }

                if ($this_cur_category['path_id'] == "3"){ //аксессуары
                    if ($import_yml_margin_type == 'percents') {
                        $New_Price = ceil( ((float)$offer->price + ( ((float)$offer->price / 100) * $import_yml_category_margin_aksessuari))  /100)*100-10;
                    } else {
                        $New_Price = (float)$offer->price + $import_yml_category_margin_aksessuari;
                    }
                }
                //Рассчитываем цену Конец
            }


            $import_yml_category_margin = $this->config->get('import_yml_category_margin_'.$offer->categoryId);

            if ($import_yml_category_margin) {
                if ($import_yml_margin_type == 'percents') {
                    $New_Price = ceil( ((float)$offer->price + ( ((float)$offer->price / 100) * $import_yml_category_margin))  /100)*100-10;
                } else {
                    $New_Price = (float)$offer->price + $import_yml_category_margin;
                }
            } else {

                $this_product_categories = $this->model_tool_import_yml->getCategoryParents((int)$offer->categoryId);
                foreach ($this_product_categories as $this_cur_category) {
                    $import_yml_category_margin = $this->config->get('import_yml_category_margin_'.$this_cur_category['path_id']);
                    if ($import_yml_category_margin) {
                        if ($import_yml_margin_type == 'percents') {
                            $New_Price = ceil( ((float)$offer->price + ( ((float)$offer->price / 100) * $import_yml_category_margin))  /100)*100-10;
                        } else {
                            $New_Price = (float)$offer->price + $import_yml_category_margin;
                        }
                    }
                }
            }



                if (isset($offer->param)) {
                    $params = $offer->param;

                    foreach ($params as $param) {
                        $attr_name = (string)$param['name'];
                        $attr_value = (string)$param;

                        if ( $attr_name == "Предмет одежды") {
                            $margin = $this->config->get('import_yml_predmet_odezhdi_' . $this->transliterate($attr_value));
                            if ($margin) {
                                if ($import_yml_margin_type == 'percents') {
                                    $New_Price = ceil( ((float)$offer->price + ( ((float)$offer->price / 100) * $margin))  /100)*100-10;
                                } else {
                                    $New_Price = (float)$offer->price + $margin;
                                }
                                break;
                            }

                        }
                    }
                }

            if (is_array($product_names_margin) || is_object($product_names_margin)) {
                foreach ($product_names_margin as $marginKey => $margin) {
                    if ( mb_stripos($productName, $marginKey) !== false) {
                        if ($import_yml_margin_type == 'percents') {
                            $New_Price = ceil( ((float)$offer->price + ( ((float)$offer->price / 100) * $margin))  /100)*100-10;
                        } else {   
                            $New_Price =(float)$offer->price + $margin;
                        }
                    }
                }
            }

            if (is_array($product_skus_margin) || is_object($product_skus_margin)) {
                foreach ($product_skus_margin as $marginKey => $margin) {
                    if ( (string)$offer['id'] == $marginKey ) {
                        if ($import_yml_margin_type == 'percents') {
                            $New_Price = ceil( ((float)$offer->price + ( ((float)$offer->price / 100) * $margin))  /100)*100-10; 
                        } else {  
                            $New_Price =(float)$offer->price + $margin;
                        }
                    }
                }
            }

            $product_special = array();

            if ($haveSpecial) {
                $product_special[0] = array (
                    'customer_group_id' => '1',
                    'priority' => '1',
                    'price' =>  $New_Price,
                    'date_start' => '2020-01-01',
                    'date_end' => '2060-01-01'
                );

                $New_Price = ceil( ($New_Price + ( ($New_Price / 100) * $haveSpecial))  /100)*100-10;
            }

            $data = array(
                'product_id' => (int)$offer['id'],
                'product_description' => $product_description,
                'product_special' => $product_special,
                'product_store' => array(0),
                'main_category_id' => $this->categoryMap[(int)$offer->categoryId],
                'product_category' => $this_product_category,
                'product_attribute' => array(),
                'model' => (string)$offer['id'],
                'image' => $image_path,
                'sku'   => (string)$offer['id'],
                'keyword' => $this->transliterate(str_replace('&quot;', "", str_replace('"', "", $productName))) . '-' . (string)$offer['id'],
                'upc'  => '',
                'ean'  => '',
                'jan'  => '',
                'isbn' => '',
                'mpn'  => '',
                'location' => '',
                'quantity' => $offer->quantity,
                'minimum' => '1',
                'subtract' => '1',
                'stock_status_id' => '5',
                'date_available' => '2016-01-01',
                'manufacturer_id' => '',
                'shipping' => 1,
                'price' => $New_Price,
                'points' => $New_Price,
                'weight' => '',
                'weight_class_id' => '',
                'length' => '',
                'width' => '',
                'height' => '',
                'length_class_id' => '',
                'status' => ($offer['available'] == 'true')? '1' : '0',
                'tax_class_id' => '',
                'sort_order' => (int)$offer['id'],
                'related_category' => '',
                'product_image' => $product_images,
                'size_id' => '1',
                'color_id' => '1'
            );

            $meta_import_yml_meta_keyword_brand = str_replace("{product_name}", $meta_product_name, $import_yml_meta_keyword_brand);
            $meta_import_yml_meta_keyword_brand = str_replace("{brand_name}", $meta_brand_name, $meta_import_yml_meta_keyword_brand);
            $meta_import_yml_meta_keyword_brand = str_replace("{category_name}", $meta_product_name, $meta_import_yml_meta_keyword_brand);
            $meta_import_yml_meta_keyword_brand = str_replace("{parent_categories_names}", $meta_parent_categories_names, $meta_import_yml_meta_keyword_brand);
            $meta_import_yml_meta_keyword_brand = str_replace("{shop_name}", $meta_shop_name, $meta_import_yml_meta_keyword_brand);
            $meta_import_yml_meta_keyword_brand = str_replace("  ", " ", $meta_import_yml_meta_keyword_brand);
            
            $meta_import_yml_meta_description_brand = str_replace("{product_name}", $meta_product_name, $import_yml_meta_description_brand);
            $meta_import_yml_meta_description_brand = str_replace("{brand_name}", $meta_brand_name, $meta_import_yml_meta_description_brand);
            $meta_import_yml_meta_description_brand = str_replace("{category_name}", $meta_product_name, $meta_import_yml_meta_description_brand);
            $meta_import_yml_meta_description_brand = str_replace("{parent_categories_names}", $meta_parent_categories_names, $meta_import_yml_meta_description_brand);
            $meta_import_yml_meta_description_brand = str_replace("{shop_name}", $meta_shop_name, $meta_import_yml_meta_description_brand);
            $meta_import_yml_meta_description_brand = str_replace("  ", " ", $meta_import_yml_meta_description_brand);
            
            $meta_import_yml_meta_title_brand = str_replace("{product_name}", $meta_product_name, $import_yml_meta_title_brand);
            $meta_import_yml_meta_title_brand = str_replace("{brand_name}", $meta_brand_name, $meta_import_yml_meta_title_brand);
            $meta_import_yml_meta_title_brand = str_replace("{category_name}", $meta_product_name, $meta_import_yml_meta_title_brand);
            $meta_import_yml_meta_title_brand = str_replace("{parent_categories_names}", $meta_parent_categories_names, $meta_import_yml_meta_title_brand);
            $meta_import_yml_meta_title_brand = str_replace("{shop_name}", $meta_shop_name, $meta_import_yml_meta_title_brand);
            $meta_import_yml_meta_title_brand = str_replace("  ", " ", $meta_import_yml_meta_title_brand);
            
            $meta_import_yml_meta_h1_brand = str_replace("{product_name}", $meta_product_name, $import_yml_meta_h1_brand);
            $meta_import_yml_meta_h1_brand = str_replace("{brand_name}", $meta_brand_name, $meta_import_yml_meta_h1_brand);
            $meta_import_yml_meta_h1_brand = str_replace("{category_name}", $meta_product_name, $meta_import_yml_meta_h1_brand);
            $meta_import_yml_meta_h1_brand = str_replace("{parent_categories_names}", $meta_parent_categories_names, $meta_import_yml_meta_h1_brand);
            $meta_import_yml_meta_h1_brand = str_replace("{shop_name}", $meta_shop_name, $meta_import_yml_meta_h1_brand);
            $meta_import_yml_meta_h1_brand = str_replace("  ", " ", $meta_import_yml_meta_h1_brand);

            if (isset($offer->vendor)) {
                $vendor_name = (string)$offer->vendor;

                $manufacturer_data = array (
                    'name' => $vendor_name,
                    'sort_order' => 0,
                    'manufacturer_description' => array (
                        1 => array (
                            'name'=> $vendor_name,
                            // 'meta_keyword' => $vendor_name,
                            'meta_keyword' => $meta_import_yml_meta_keyword_brand,
                            // 'meta_description' => $vendor_name,
                            'meta_description' => $meta_import_yml_meta_description_brand,
                            'description' => $vendor_name,
                            // 'meta_title' => $vendor_name,
                            'meta_title' => $meta_import_yml_meta_title_brand,
                            // 'meta_h1' => $vendor_name,
                            'meta_h1' => $meta_import_yml_meta_h1_brand,
                        )
                    ),
                    'manufacturer_store' => array ( 0 ),
                    'image' => 'catalog/brands/' . str_replace(" ", "", $this->transliterate($vendor_name)) . '.jpg',
                    'keyword' => $this->transliterate(str_replace('"', "", $vendor_name)),
                );

                if (!isset($vendorMap[$vendor_name])) {
                    $vendorMap[$vendor_name] = $this->model_catalog_manufacturer->addManufacturer($manufacturer_data);
                } else {
                    $this->model_catalog_manufacturer->editManufacturer($vendorMap[(string)$offer->vendor], $manufacturer_data);
                }

                $data['manufacturer_id'] = $vendorMap[(string)$offer->vendor];
            }

            if (isset($offer->options)) {

                $options_count = 0;

                $options_value = $offer->options;
                $current_options = explode(";", $options_value);

                $this->db->query( "UPDATE  `" . DB_PREFIX . "product_option_value` SET quantity = 0 WHERE product_id = '" . (int)$offer['id'] . "'" );

                foreach ($current_options as $option) {
                    $current_option = explode("-", $option);
                    if (isset($current_option[1])) {

                        $option_name = $current_option[0];
                        $option_value = $current_option[1];

                        if ($option_value > 0) {

                            if (array_key_exists($option_name, $optionsMap) === false) {

                                $this->db->query( "INSERT INTO `" . DB_PREFIX . "option_value` SET option_id = '" . $optionId . "', image = '', sort_order = '" .  str_replace(",", ".", (int)($option_name*10)) . "'" );
                                
                                $option_value_id = $this->db->getLastId();
                                
                                $this->db->query( "INSERT INTO `" . DB_PREFIX . "option_value_description` SET option_value_id = '" . $option_value_id . "', language_id = '1', option_id = '" . $optionId . "', name = '" .  $option_name . "'"  );
        
                                $optionsMap[$option_name] = array (
                                    'option_value_id' => $option_value_id,
                                    'name' => $option_name,
                                    'image' => '',
                                    'sort_order' => $option_name*10
                                );
                            } else {
                                $option_value_id = $optionsMap[$option_name]['option_value_id'];
                            }
                            

                            $existing_product_option = $this->db->query( "SELECT * FROM `" . DB_PREFIX . "product_option` WHERE product_id = '" . (int)$offer['id'] . "'" );

                            if (!isset($existing_product_option->row['product_option_id'])) {
                                $this->db->query( "INSERT INTO `" . DB_PREFIX . "product_option` SET product_id = '" .  (int)$offer['id'] . "', option_id = '" . $optionId . "', value = '', required = '1'" );

                                $product_option_id = $this->db->getLastId();
                            } else {
                                $product_option_id = $existing_product_option->row['product_option_id'];
                            }

                            $product_option_value_id =  $this->db->query( "SELECT product_option_value_id FROM `" . DB_PREFIX . "product_option_value` WHERE product_id = '" . (int)$offer['id'] . "' AND product_option_id = '" . $product_option_id . "' AND option_value_id = '" . $optionsMap[$option_name]['option_value_id'] . "'"  );

                            if ($product_option_value_id->row) {
                                $this->db->query( "UPDATE  `" . DB_PREFIX . "product_option_value` SET quantity = '". $option_value ."' WHERE  product_option_value_id = '" . $product_option_value_id->row['product_option_value_id'] . "'"  );
                            } else {
                                $this->db->query( "INSERT INTO `" . DB_PREFIX . "product_option_value` SET product_option_id = '" . $product_option_id . "', product_id = '" .  (int)$offer['id'] . "', option_id = '" . $optionId . "', option_value_id = '" . $optionsMap[$option_name]['option_value_id'] . "', quantity = '". $option_value ."', subtract = '1', price = '0.0000', price_prefix = '+', points = '0', points_prefix = '+', weight = '0.00', weight_prefix = '+' " );
                            }
                        }
                    }
                }
            }

            if (isset($offer->param)) {
                $params = $offer->param;

                foreach ($params as $param) {
                    $attr_name = (string)$param['name'];
                    $attr_value = (string)$param;
                    if ($attr_name == "Скидка") {
                        $attr_value = (string)$param . "%";
                    }
                    if ( $attr_name == "Предмет одежды") {
                        $attrSortOrder = 0;
                    } else {
                        $attrSortOrder = 1;
                    }

                    if (array_key_exists($attr_name, $attrMap) === false) {
                        $attr_data = array (
                            'sort_order' => $attrSortOrder,
                            'attribute_group_id' => $attrGroupId,
                            'attribute_description' => array (
                                1 => array (
                                    'name' => $attr_name,
                                )
                            ),
                        );

                        $attrMap[$attr_name] = $this->model_catalog_attribute->addAttribute($attr_data);
                    }

                    $data['product_attribute'][] = array (
                        'attribute_id' => $attrMap[$attr_name],
                        'product_attribute_description' => array (
                            1 => array (
                                'text' => $attr_value,
                            )
                        )
                    );
                }
            }

            if ($this->model_catalog_product->getProduct((int)$offer['id'])) {
                // $data = $this->changeDataByColumns($this->skuProducts[ (int)$offer['id'] ], $data);
                $this->model_tool_import_yml->editProduct((int)$offer['id'], $data);
                $this->skuProducts[ (int)$offer['id'] ] = (int)$offer['id'];
                $this->productsUpdated++;
            } else {
                $this->skuProducts[ (int)$offer['id'] ] = $this->model_catalog_product->addProduct($data);
                $this->productsAdded++;
            }

            --$flushCounter;

            if ($flushCounter <= 0) {
                $loaded = $i;

                /*$this->model_setting_setting->editSetting('import_yml_file', array(
                    'file_hash' => md5($this->file),
                    'offers' => count($offers->offer),
                    'loaded' => $loaded
                ));*/

                $flushCounter = $this->flushCount;
            }

            $this->model_setting_setting->editSetting('start_import_yml_from', array(
                'start_import_yml_from_product_id' => (int)$offer['id']
            ));
        }
        
        $this->model_setting_setting->editSetting('start_import_yml_from', array(
            'start_import_yml_from_product_id' => ''
        ));

        $strResult = "Products not in file: " . $productsNotInFile . "\r\n" . "Added: " . $this->productsAdded . "\r\n" . "Updated: " . $this->productsUpdated . "\r\n" . "Disabled: " . $this->productsSkipped . "\r\n" . "Done: " . date("Y-m-d H:i:s") . "\r\n\r\n";

        $logFile = fopen(DIR_LOGS . "import.log", 'a') or die("не удалось создать файл");
        fwrite($logFile, $strResult);

        $categories = $this->db->query( "SELECT DISTINCT c.category_id FROM ".DB_PREFIX."category as c LEFT JOIN ".DB_PREFIX."product_to_category as p2c ON c.category_id = p2c.category_id LEFT JOIN " . DB_PREFIX . "product as p ON p.product_id = p2c.product_id WHERE c.status = 1 AND NOT EXISTS ( SELECT p2.product_id FROM ".DB_PREFIX."product as p2 LEFT JOIN ".DB_PREFIX."product_to_category as p2c2 ON p2.product_id = p2c2.product_id WHERE p2.status = 1 AND p2c2.category_id = c.category_id LIMIT 1 )"  );

        fwrite($logFile, "\r\nDisabled empty categories: " . date("Y-m-d H:i:s"). "\r\n");
        if ($categories) {
            foreach ($categories->rows as $category) {
                fwrite($logFile, $category['category_id'] . "\r\n");
                $this->db->query( "UPDATE ".DB_PREFIX."category SET status='0' WHERE `category_id`='" . $category['category_id'] . "'" );
            }
        } else {
            fwrite($logFile, "Нет");
        }

        fclose($logFile);
        echo "Done";
    }


    private function loadImageFromHost($link, $img_path)
    {
        if (!file_exists($img_path)) {
            $isimage = @getimagesize($link);
            if ( !$isimage ) {
                return false;
            }	elseif ( !in_array($isimage[2], array(1,2,3)) ) {
                return false;
            }   else {
                $ch = curl_init($link);
                $fp = fopen($img_path, "wb");
                if ($fp) {
                    $options = array(CURLOPT_FILE => $fp,
                        CURLOPT_HEADER => 0,
                        CURLOPT_FOLLOWLOCATION => 1,
                        CURLOPT_TIMEOUT => 60,
                    );

                    curl_setopt_array($ch, $options);

                    curl_exec($ch);
                    curl_close($ch);
                    fclose($fp);
                }

                $logFile = fopen(DIR_LOGS . "import.log", 'a') or die("не удалось создать файл");
                fwrite($logFile,"Downloading image: " . $link  . "\r\n");
                fclose($logFile);

                return file_exists($img_path);
            }
        }

        return true;
    }

    private function changeDataByColumns($product_id, $data)
    {
        $productData = $this->model_catalog_product->getProduct($product_id);
        $productAttributes = $this->model_catalog_product->getProductAttributes($product_id);

        if (empty($this->columnsUpdate['name'])) {
            $data['product_description'][1]['name'] = $productData['name'];
        }

        if (empty($this->columnsUpdate['description'])) {
            $data['product_description'][1]['description'] = $productData['description'];
        }

        if (empty($this->columnsUpdate['price'])) {
            $data['price'] = $productData['price'];
        }

        if (empty($this->columnsUpdate['image'])) {
            $data['image'] = $productData['image'];
        }

        if (empty($this->columnsUpdate['manufacturer'])) {
            $data['manufacturer_id'] = $productData['manufacturer_id'];
        }

        if (empty($this->columnsUpdate['attributes'])) {
            $data['product_attribute'] = $productAttributes;
        }

        return $data;
    }

    private function transliterate($st){
        $arr = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v','г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж'=>'zh','з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's','т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч'=>'ch', 'ш'=>'sh', 'щ'=>'sch', 'ъ'=>'', 'ы' => 'y', 'ь'=>'',
            'э' => 'e', 'ю' => 'u', 'я' => 'a', 'А' => 'a', 'Б' => 'b', 'В' => 'v',
            'Г' => 'g', 'Д' => 'd', 'Е' => 'e', 'Ё' => 'e', 'Ж'=>'Zh','З' => 'z', 'И' => 'i',
            'Й' => 'y', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n', 'О' => 'o',
            'П' => 'p', 'Р' => 'r', 'С' => 's','Т' => 't', 'У' => 'u', 'Ф' => 'f',
            'Х' => 'h', 'Ц' => 'c', 'Ч'=>'Ch', 'Ш'=>'Sh', 'Щ'=>'Sch', 'Ъ'=>'',
            'Ы' => 'y', 'Ь'=>'', 'Э' => 'e', 'Ю' => 'u', 'Я' => 'a', '/' => '',
            '+' => '', '.' => '-', ',' => '-', '!' => '-', ' ' => '-', '"' => '', '&' => 'and', '?' => '',

        );
        $key = array_keys($arr);
        $val = array_values($arr);
        $st = str_replace($key,$val,$st);

        return $st;
    }
}