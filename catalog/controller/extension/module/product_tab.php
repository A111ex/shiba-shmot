<?php
class ControllerExtensionModuleProductTab extends Controller {
	public function index($setting) {

		if(!isset($this->request->get['route']) || $this->request->get['route'] != 'product/product'){
		$this->document->addScript('catalog/view/javascript/jquery/tabs.js');
		}

		static $module = 0;

		$this->load->language('extension/module/product_tab');

      	$data['heading_title'] = $this->language->get('heading_title');

      	$data['tab_latest'] = $this->language->get('tab_latest');
      	$data['tab_featured'] = $this->language->get('tab_featured');
      	$data['tab_bestseller'] = $this->language->get('tab_bestseller');
      	$data['tab_special'] = $this->language->get('tab_special');

		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_sku'] = $this->language->get('text_sku');
		$data['text_economy'] = $this->language->get('text_economy');
		$data['text_comparison'] = $this->language->get('text_comparison');
		$data['text_model'] = $this->language->get('text_model');

		$data['text_manufacturer'] = $this->language->get('text_manufacturer');

		$data['text_product_in_stock'] = $this->language->get('text_product_in_stock');
		$data['quickview_text'] = $this->language->get('quickview_text');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');



		$data['latest_products'] = array();

		if ($setting['product_latest_manual']) {
			if ($setting['latest_products'] && $setting['product_latest']) {

				if (empty($setting['limit'])) {
					$setting['limit'] = 5;
				}
	
				$products_latest = array_slice($setting['product_latest'], 0, (int)$setting['limit']);
			}

		} else {
			if ($setting['latest_products']) {
				$products_latest = $this->model_catalog_product->getLatestProducts((int)$setting['limit']);
			}
		}
		
/*
		//Latest Products
		
		$data['latest_products'] = array();

		if($setting['latest_products']){
		
						
			$latest_results = $this->model_catalog_product->getLatestProducts((int)$setting['limit']);

			if ($latest_results) {
				foreach ($latest_results as $result) {*/

		//Latest Products

		

		if ($setting['latest_products'] && $setting['product_latest']) {

			foreach ($products_latest as $product) {

				if ($setting['product_latest_manual']) {
					$result = $this->model_catalog_product->getProduct($product);
				} else {
					$result = $product;
				}

				if ($result) {

					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

					} else {
						$price = false;
					}

					if ((float)$result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ((float)$result['special']) {
						$data['action_percent'] = 100-round($result['special']/$result['price']*100, 0);
						$economy = $this->currency->format($this->tax->calculate($result['price'] - $result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$data['action_percent']= false;
						$economy = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format(((float)$result['special'] ? $result['special'] : $result['price']), $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $result['rating'];
					} else {
						$rating = false;
					}

					if ($special) {
						$action_percent = 100-round($result['special']/$result['price']*100, 0);
					} else {
						$action_percent = "0";
					}

					$data['latest_products'][] = array(
						'product_id'  => $result['product_id'],
						'model'  => $result['model'],
						'manufacturer'  => $result['manufacturer'],
						'manufacturers' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id']),
						'thumb'       => $image,
						'name'        => $result['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'action_percent' => $action_percent,
						'quantity' 	  => $result['quantity'],
						'stock'       => $result['stock_status'],
						'reviews' 	  => $result['reviews'],
						'minimum'     => $result['minimum'],
						'tax'         => $tax,
						'economy'     => $economy,
						'sku'         => $result['sku'],
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
					);
				}
			}
		}

		//Specials product

		$data['special_products'] = array();

		if($setting['special_products']){

			$special_data = array(
				'sort'  => 'pd.model',
				'order' => 'DESC',
				'start' => 0,
				'limit' => $setting['limit']
			);

			$special_results = $this->model_catalog_product->getProductSpecials($special_data);

			if ($special_results) {
				foreach ($special_results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

					} else {
						$price = false;
					}


					if ((float)$result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ((float)$result['special']) {
						$data['action_percent'] = 100-round($result['special']/$result['price']*100, 0);
						$economy = $this->currency->format($this->tax->calculate($result['price'] - $result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$data['action_percent']= false;
						$economy = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $result['rating'];
					} else {
						$rating = false;
					}

					$data['special_products'][] = array(
						'product_id'  => $result['product_id'],
						'model'  => $result['model'],
						'manufacturer'  => $result['manufacturer'],
						'manufacturers' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id']),
						'thumb'       => $image,
						'name'        => $result['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'quantity' 	  => $result['quantity'],
						'stock'       => $result['stock_status'],
						'reviews' 	  => $result['reviews'],
						'action_percent' => $action_percent,
						'minimum'     => $result['minimum'],
						'tax'         => $tax,
						'economy'     => $economy,
						'sku'         => $result['sku'],
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
					);
				}
			}
		}

		//BestSeller
		$data['bestseller_products'] = array();

		if($setting['bestseller_products']){

			$bestseller_results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);

			if ($bestseller_results) {
				foreach ($bestseller_results as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

					} else {
						$price = false;
					}

					if ((float)$result['special']) {
						$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ((float)$result['special']) {
						$data['action_percent'] = 100-round($result['special']/$result['price']*100, 0);
						$economy = $this->currency->format($this->tax->calculate($result['price'] - $result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$data['action_percent']= false;
						$economy = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format(((float)$result['special'] ? $result['special'] : $result['price']), $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $result['rating'];
					} else {
						$rating = false;
					}

					if ($special) {
						$action_percent = 100-round($result['special']/$result['price']*100, 0);
					} else {
						$action_percent = "0";
					}

					$data['bestseller_products'][] = array(
						'product_id'  => $result['product_id'],
						'model'  => $result['model'],
						'manufacturer'  => $result['manufacturer'],
						'manufacturers' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id']),
						'thumb'       => $image,
						'name'        => $result['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'quantity' 	  => $result['quantity'],
						'stock'       => $result['stock_status'],
						'action_percent' => $action_percent,
						'reviews' 	  => $result['reviews'],
						'minimum'     => $result['minimum'],
						'tax'         => $tax,
						'economy'     => $economy,
						'sku'         => $result['sku'],
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
					);
				}
			}
		}

		//Featured
		$data['featured_products'] = array();

		if ($setting['featured_products'] && $setting['product_featured']) {


			if (empty($setting['limit'])) {
				$setting['limit'] = 5;
			}

			$products_featured = array_slice($setting['product_featured'], 0, (int)$setting['limit']);


			foreach ($products_featured as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

					} else {
						$price = false;
					}


					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ((float)$product_info['special']) {
						$data['action_percent'] = 100-round($product_info['special']/$product_info['price']*100, 0);
						$economy = $this->currency->format($this->tax->calculate($product_info['price'] - $product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$data['action_percent']= false;
						$economy = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format(((float)$product_info['special'] ? $product_info['special'] : $product_info['price']), $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}

					if ($special) {
						$action_percent = 100-round($product_info['special']/$product_info['price']*100, 0);
					} else {
						$action_percent = "0";
					}

					$data['featured_products'][] = array(
						'product_id'  => $product_info['product_id'],
						'model'  => $product_info['model'],
						'manufacturer'  => $product_info['manufacturer'],
						'manufacturers' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']),
						'thumb'       => $image,
						'name'        => $product_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'quantity' 	  => $product_info['quantity'],
						'stock'       => $product_info['stock_status'],
						'action_percent' => $action_percent,
						'reviews' 	  => $product_info['reviews'],
						'minimum'     => $product_info['minimum'],
						'tax'         => $tax,
						'economy'     => $economy,
						'sku'         => $product_info['sku'],
						'rating'      => $rating,
						'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
					);
				}
			}
		}

		$data['module'] = $module++;

		return $this->load->view('extension/module/product_tab.tpl', $data);

	}
}
