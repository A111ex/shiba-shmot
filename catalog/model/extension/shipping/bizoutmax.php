<?php
class ModelExtensionShippingBizoutmax extends Model {
	function getQuote($address) {
		$this->load->language('extension/shipping/bizoutmax');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('bizoutmax_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if (!$this->config->get('bizoutmax_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status && isset($address['postcode'])) {

			$quote_data = array();


			$url = 'http://bizoutmax.ru/index.php?route=drop/proxy/token';
			$params = array(
				'grant_type'    => "password",
				'username'      => $this->config->get('bizoutmax_login'),
				'password'      => base64_decode($this->config->get('bizoutmax_password'))
			);

			$result = file_get_contents($url, false, stream_context_create(array(
				'http' => array(
					'method'  => 'POST',
					'header'  => 'Content-type: application/x-www-form-urlencoded',
					'content' => http_build_query($params)
				)
			)));

			$obj = json_decode($result);

			$url = 'http://bizoutmax.ru/index.php?route=drop/proxy/calculateShipping';
			
			$products = $this->cart->getProducts();

			if (!$products){
				return;
			}

			$params = ' {
				"postcode": "'.$address['postcode'].'",
				"products": [';
			foreach ($products as $key => $product) {
				if ($key ==0){
					$params .= '{ "product_id": "'.$product['product_id'].'",
							"quantity": "'.$product['quantity'].'" } ';
				} else {
					$params .= ', { "product_id": "'.$product['product_id'].'",
							"quantity": "'.$product['quantity'].'" } ';
				}
			}
			$params .='] }';


			$ch = curl_init($url);

			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'X-Authorization: Bearer '. $obj->{'access_token'}));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			$result = json_decode($result);
			curl_close($ch);

			if (isset($result->error)) {
				return;
			}

			foreach ($result as $key => $description) {
												
				$code = 'bizoutmax_' . $key;
				
				$quote_data[$code] = array(
					'code'         => 'bizoutmax.' . $code,
					'title'        => $description->name,
					'cost'         => $description->delivery_cost,
					'tax_class_id' => $this->config->get('bizoutmax_tax_class_id'),
					'text'         => $this->currency->format($this->tax->calculate($description->delivery_cost, $this->config->get('bizoutmax_tax_class_id'), $this->config->get('config_tax')), $this->session->data['currency'])
				);
			}

			$method_data = array(
				'code'       => 'bizoutmax',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('bizoutmax_sort_order'),
				'error'      => false
			);
		}

		return $method_data;
	}
}