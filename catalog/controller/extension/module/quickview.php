<?php
class ControllerExtensionModuleQuickView extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/quickview');
		$language_id = $this->config->get('config_language_id');

		$this->load->model('setting/setting');
		$this->load->model('catalog/manufacturer');
		$this->load->model('catalog/product');

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {

			if (!$product_info['sizes_help_image']) {
				$product_info['sizes_help_image'] = $this->model_catalog_product->getSizesHelpImage($product_id);
			}

			if ($product_info['sizes_help_image'] == 'no_image') {
				$data['sizes_help_image'] = false;
			} else {
				$data['sizes_help_image'] = $product_info['sizes_help_image'];
			}

			// Product Name
			$data['heading_title'] = $product_info['name'];

			foreach ($this->cart->getProducts() as $result) {
				if ($result['product_id'] == $this->request->get['product_id']) {
					$data['in_cart'] = $result['product_id'];
				}
			}

			// Text
			$data['text_select'] = $this->language->get('text_select');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_stock'] = $this->language->get('text_stock');
			$data['text_discount'] = $this->language->get('text_discount');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$data['text_write'] = $this->language->get('text_write');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
			$data['text_related'] = $this->language->get('text_related');
			$data['text_payment_recurring'] = $this->language->get('text_payment_recurring');
			$data['text_loading'] = $this->language->get('text_loading');
			$data['text_compare'] = $this->language->get('text_compare');
			$data['text_wishlist'] = $this->language->get('text_wishlist');
			$data['text_economy'] = $this->language->get('text_economy');
			$data['text_popup_continue'] = $this->language->get('text_popup_continue');
			$data['text_in_cart'] = $this->language->get('text_in_cart');
			$data['text_popup_title'] = $this->language->get('text_popup_title');
			$data['text_sku'] = $this->language->get('text_sku');
			$data['text_description'] = $this->language->get('text_description');
			$data['text_attribute'] = $this->language->get('text_attribute');
			$data['text_review'] = sprintf($this->language->get('text_review'), $product_info['reviews']);
			$data['text_more_info'] = $this->language->get('text_more_info');

			// Any text
			$data['product_required_text'] = $this->language->get('product_required_text');
			$data['back_button_text'] = $this->language->get('back_button_text');
			$data['more_button_text'] = $this->language->get('more_button_text');
			$data['fastorder_text'] = $this->language->get('fastorder_text');

			// Entry
			$data['entry_qty'] = $this->language->get('entry_qty');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_review'] = $this->language->get('entry_review');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_good'] = $this->language->get('entry_good');
			$data['entry_bad'] = $this->language->get('entry_bad');

			// Button
			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_upload'] = $this->language->get('button_upload');
			$data['button_continue'] = $this->language->get('button_continue');

			// Variables
			$data['product_id'] = (int)$this->request->get['product_id'];
			$data['manufacturer'] = $product_info['manufacturer'];
			$data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$data['model'] = $product_info['model'];
			$data['sku'] = $product_info['sku'];
			$data['quantity'] = $product_info['quantity'];
			$data['reward'] = $product_info['reward'];
			$data['points'] = $product_info['points'];
			$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');

			// Links
			$data['cart'] = $this->url->link('checkout/cart');
			$data['product_href'] = $this->url->link('product/product', 'product_id=' . $product_info['product_id']);

			// Stock status
			if ($product_info['quantity'] <= 0) {
				$data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$data['stock'] = $product_info['quantity'];
			} else {
				$data['stock'] = $this->language->get('text_instock');
			}

			// Adding the image model
			$this->load->model('tool/image');

			// Manufacturer image
			$manufacturer_image = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
			if ($manufacturer_image) {
                $data['manufacturers_img'] = $this->model_tool_image->resize($manufacturer_image['image'], 70, 70);
            } else {
                $data['manufacturers_img'] = false;
            }

			// Product images
			if ($product_info['image']) {
				$data['thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
				$data['small_thumb'] = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
			} else {
				$data['thumb'] = '';
				$data['small_thumb'] = '';
			}

			$data['images'] = array();

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			foreach ($results as $result) {
				$data['images'][] = array(
					'thumb' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height')),
					'big_thumb' => $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'))
				);
			}


			// If the buyer is not registered or the function does not show prices is enabled
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$data['price_2'] = $this->currency->format($this->tax->calculate($product_info['price_2'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$data['price_3'] = $this->currency->format($this->tax->calculate($product_info['price_3'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$data['price_4'] = $this->currency->format($this->tax->calculate($product_info['price_4'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$data['price_old'] = $this->currency->format($this->tax->calculate($product_info['price_old'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$data['price_economy'] = $this->currency->format($this->tax->calculate($product_info['price_old']-$product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$data['price'] = false;
				$data['price_2'] = false;
				$data['price_3'] = false;
				$data['price_4'] = false;
				$data['price_old'] = false;
				$data['price_economy'] = false;
			}

			if ($data['price'] == $data['price_2'] || $data['price'] == $data['price_3'] || $data['price'] == $data['price_4'] || $this->customer->getGroupId() == 5 )
			{
				$data['price_1'] = $data['price_old'];
			}
			else
			{
				$data['price_1'] = $data['price'];
			}	

			if ((float)$product_info['special']) {
				$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$data['special'] = false;
			}

			if ((float)$product_info['special']) {
				$data['economy'] = $this->currency->format($this->tax->calculate($product_info['price'] - $product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$data['economy'] = false;
			}

			if ($this->config->get('config_tax')) {
				$data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
			} else {
				$data['tax'] = false;
			}

			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

			$data['discounts'] = array();

			foreach ($discounts as $discount) {
				$data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
				);
			}

			// Add options
			$data['options'] = array();

			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
				$product_option_value_data = array();

				foreach ($option['product_option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
							$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
							$price_2 = $this->currency->format($this->tax->calculate($option_value['price_2'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
							$price_3 = $this->currency->format($this->tax->calculate($option_value['price_3'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
							$price_4 = $this->currency->format($this->tax->calculate($option_value['price_4'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
							$price_old = $this->currency->format($this->tax->calculate($option_value['price_old'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
						} else {
							$price = false;
							$price_2 = false;
							$price_3 = false;
							$price_4 = false;
							$price_old = false;
						}

						if($price == $price_2 || $price == $price_3 || $price == $price_4 )
						{
							$price_1 = $price_old;
						}
						else
						{
							$price_1 = $price;
						}

						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'image'                   => $option_value['image'] ? $this->model_tool_image->resize($option_value['image'], 50, 50) : '',
							'price'                   => $price,
							'price_1'                   => $price_1,
							'price_2'                   => $price_2,
							'price_3'                   => $price_3,
							'price_4'                   => $price_4,
							'price_old'                   => $price_old,
							'price_prefix'            => $option_value['price_prefix']
						);
					}
				}

				$data['options'][] = array(
					'product_option_id'    => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $option['option_id'],
					'name'                 => $option['name'],
					'type'                 => $option['type'],
					'value'                => $option['value'],
					'required'             => $option['required']
				);
			}

			if ($product_info['minimum']) {
				$data['minimum'] = $product_info['minimum'];
			} else {
				$data['minimum'] = 1;
			}

			$data['review_status'] = $this->config->get('config_review_status');
			$data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
			$data['rating'] = (int)$product_info['rating'];

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'));
			} else {
				$data['captcha'] = '';
			}

			$data['share'] = $this->url->link('extension/module/quickview', 'product_id=' . (int)$this->request->get['product_id']);

			$data['attribute_group'] = $this->model_catalog_product->getProductOnlyAttributes($this->request->get['product_id']);

			$data['recurrings'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);

			$this->model_catalog_product->updateViewed($this->request->get['product_id']);

			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('extension/module/quickview', $data));
		}
	}

	public function write() {
		$this->load->language('extension/module/quickview');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
				$json['error'] = $this->language->get('error_rating');
			}

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('catalog/review');

				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}


	public function getRecurringDescription() {
		$this->load->language('extension/module/quickview');
		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['recurring_id'])) {
			$recurring_id = $this->request->post['recurring_id'];
		} else {
			$recurring_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);
		$recurring_info = $this->model_catalog_product->getProfile($product_id, $recurring_id);

		$json = array();

		if ($product_info && $recurring_info) {
			if (!$json) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
				);

				if ($recurring_info['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				if ($recurring_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}