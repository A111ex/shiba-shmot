<?php
class ControllerExtensionModuleSmartFilter extends Controller {
	public function index() {
		$data=array();
		
		$this->document->addStyle('catalog/view/smart_filter/smart_filter.css');
		$this->document->addScript('catalog/view/smart_filter/smart_filter.js');
		$this->load->language('smart_filter/smart_filter');
		$this->load->model('smart_filter/smart_filter');

		$data['text_price'] = $this->language->get('text_price');
		$data['text_categories'] = $this->language->get('text_categories');
		$data['text_manufrecturer'] = $this->language->get('text_manufrecturer');


		$data['pricr_filter_status'] = $this->config->get('smart_filter_price');
		$data['manufacturer_filter_status'] = $this->config->get('smart_filter_manufacturer');
		$data['category_filter_status'] = $this->config->get('smart_filter_category');
		$data['img_url']=HTTPS_SERVER.'image/';
		$data['no_image']='image/catalog/no_image.jpg';
		$data['optin_status'] = $this->config->get('smart_filter_option');


		$parts = explode('_', (string)$this->request->get['path']);
		$category_id = (int)array_pop($parts);
		
		$category_id=($category_id)?$category_id:0;
		$optionFilter = array(
			'category_id' => $category_id
		);
		$data['options'] = $this->model_smart_filter_smart_filter->getOptions($optionFilter);

		$manufacturers_status = $this->config->get('smart_filter_manufacturer');
		$manufacturers=$this->model_smart_filter_smart_filter->get_manufacturer($category_id);
		
		
		// if(!empty($results)){
		// 	foreach ($results as $result) {
				
		// 		$option_value=$this->model_smart_filter_smart_filter->get_option_value($result['option_id']);			

		// 		if(!empty($option_value)){
		// 			$data['options'][] = array(
		// 				'option_id'  => $result['option_id'],
		// 				'name'       => $result['name'],
		// 				'filter_name'=> str_replace(' ','_',strtolower($result['name'])),
		// 				'option_values' => $option_value
		// 			);
		// 		}
		// 	}
		// }	
		
		// echo '!!!!!!!<br/>';
		// echo '!!!!!!!<br/><pre>';
		// print_r($data['options']);
		// echo '</pre>';
 
		if(!empty($manufacturers)){
			foreach ($manufacturers as $manufacturer) {
			$data['manufacturers'][] = array(
				'manufacturer_id'  	=> $manufacturer['manufacturer_id'],
				'name'       		=> $manufacturer['name'],
				'filter_name'		=> str_replace(' ','_',strtolower($manufacturer['name'])),
				'image'      		=> (file_exists(DIR_IMAGE.$manufacturer['image']))?$this->model_tool_image->resize($manufacturer['image'], 25, 25):'',
			);
			
			}
		}
	
		$data['attribute_status'] = $this->config->get('smart_filter_atribute');

		$attributesFilter = array(
			'category_id' => $category_id,
			'manufacturer_id' => ''
		);
		$data['attributes']=$this->model_smart_filter_smart_filter->getProductAttributes($attributesFilter);
		
		

		
			$path = '';

			$parts = explode('_', (string)$this->request->get['path']);
			$category_id = (int)array_pop($parts);
			$data['prices'] = $this->model_smart_filter_smart_filter->get_product_min_max_price($category_id);
			$data['categories'] = array();
		$url = '';
		$categories = $this->model_smart_filter_smart_filter->getCategories(0);
		foreach ($categories as $category) {
			
				// Level 2
		
				$children_data = array();

				$children = $this->model_smart_filter_smart_filter->getCategories($category['category_id']);
				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'],
						'category_id'  => $child['category_id'],
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . $url )

					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'category_id'     => $category['category_id'],
					'children' => $children_data,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
				
			}

			$data['category_id']=$category_id;
			$data['request'] = base64_encode(json_encode($this->request->get));
			$data['request_plan'] = 'index.php?'.$this->request->server['QUERY_STRING'];
			$status = $this->config->get('smart_filter_status');
			
			if($status==1){
			$data['smart_filter_url'] = $this->url->link('smart_filter/smart_filter', '', true);
			}
			return $this->load->view('smart_filter/smart_filter', $data);	
	}
}