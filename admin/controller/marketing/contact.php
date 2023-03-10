<?php
class ControllerMarketingContact extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('marketing/contact');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_default'] = $this->language->get('text_default');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_customer_all'] = $this->language->get('text_customer_all');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_customer_group'] = $this->language->get('text_customer_group');
		$data['text_affiliate_all'] = $this->language->get('text_affiliate_all');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_to'] = $this->language->get('entry_to');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_affiliate'] = $this->language->get('entry_affiliate');
		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_subject'] = $this->language->get('entry_subject');
		$data['entry_message'] = $this->language->get('entry_message');

		$data['help_customer'] = $this->language->get('help_customer');
		$data['help_affiliate'] = $this->language->get('help_affiliate');
		$data['help_product'] = $this->language->get('help_product');

		$data['button_send'] = $this->language->get('button_send');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['token'] = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], true)
		);

		$data['cancel'] = $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], true);

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/contact', $data));
	}

	public function send_new_products() {
		
		if (!isset($_GET['pass']) || $_GET['pass'] != 'send_new_products') {
            return;
        } else {

			

			// getting emails BEGIN
			$this->load->model('customer/customer');
			$this->load->model('marketing/affiliate');

			$emails = array();
			$news_emails = array();
			$subscribed_customers_emails = array();

			$emails_data = array(
				'filter_newsletter' => 1,
				'start' => 0,
				'limit' => 999999999
			);

			$AllNewsletters = $this->model_marketing_affiliate->getAllNewsletters($emails_data);

			foreach ($AllNewsletters as $result) {
				$news_emails[] = $result['news_email'];
			}

			$Customers = $this->model_customer_customer->getCustomers($emails_data);

			foreach ($Customers as $result) {
				$subscribed_customers_emails[] = $result['email'];
			}

			$emails = array_merge( $news_emails, $subscribed_customers_emails );

			$emails = array_unique( $emails );
			// getting emails END


			$this->load->model('setting/store');


			$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);

			if ($store_info) {
				$store_name = $store_info['name'];
			} else {
				$store_name = $this->config->get('config_name');
			}
			
			$this->load->model('setting/setting');
			$setting = $this->model_setting_setting->getSetting('config', $this->request->post['store_id']);
			$store_email = isset($setting['config_email']) ? $setting['config_email'] : $this->config->get('config_email');


			// $this->model_setting_setting->editSetting('last_newsletter', array(
			// 		'last_newsletter_product_id' => '22397'
			// 	));
			if ($emails) {

				if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
					$siteUrl = HTTPS_CATALOG;
				} else {
					$siteUrl['base'] = HTTP_CATALOG;
				}

				$message  = '<html dir="ltr" lang="en">' . "\n";
				$message .= '  <head>' . "\n";
				$message .= '    <title>?????????????? ???? ?????????? ' . $store_name . '</title>' . "\n";
				// $message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
				$message .= '  </head>' . "\n";

				$message .= '  <body>' . "\n";

				$message .= '    <div style="text-align: center; width: 100%; margin-top: 20px">' . "\n";

				$message .= '      <a href="' . $siteUrl . 'krossovki/" style="color: #333; text-decoration: none; font-size: 16px; padding: 10px;" >??????????????????</a>' . "\n";
				$message .= '      <a href="' . $siteUrl . 'odezhda/" style="color: #333; text-decoration: none; font-size: 16px; padding: 10px;">????????????</a>' . "\n";
				$message .= '      <a href="' . $siteUrl . 'aksessuary/" style="color: #333; text-decoration: none; font-size: 16px; padding: 10px;">????????????????????</a>' . "\n";

				$message .= '      <a style="text-decoration: none;" href="' . $siteUrl . '"><h1 style="color: #333; margin-top: 40px; margin-bottom: 20px; text-transform: uppercase; font-size: 24px;">?????????????? ???? ?????????? ' . $store_name . '</h1></a>' . "\n";

				$this->load->model('catalog/product');
				$this->load->model('tool/image');

				$last_newsletter_product_id = $this->config->get('last_newsletter_product_id');
				if (!$last_newsletter_product_id) {
					$lastProduct = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` ORDER BY product_id DESC");					
					$last_newsletter_product_id == $lastProduct->row['product_id'];
					$this->model_setting_setting->editSetting('last_newsletter', array(
						'last_newsletter_product_id' => (int)$last_newsletter_product_id
					));
				}

				// $this->model_setting_setting->editSetting('last_newsletter', array(
				// 	'last_newsletter_product_id' => '22397'
				// ));
				
				$last_newsletter_product_id = $this->config->get('last_newsletter_product_id');
				$products = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "product` WHERE product_id > " . $last_newsletter_product_id);

				$messageStr .= '';

				foreach ($products->rows as $productData) {
					$product = $this->model_catalog_product->getProduct($productData['product_id']);

					if ($product['image']) {
						$last_newsletter_product_id = $productData['product_id'];
						$messageStr .= '      <div>' . "\n";
						$messageStr .= '        <a style="display: block;" href="' . $siteUrl . 'index.php?route=product/product&product_id=' . $product['product_id'] . '"><img src="';
						
						$messageStr .=  $this->model_tool_image->resize($product['image'], 500, 375);
						
						$messageStr .= '" alt="' . $product['name'] . '" title="' .  $product['name'] . '"/></a></div>' . "\n";

						$messageStr .= '        <a style="display: block; color: #333; text-decoration: none; font-size: 16px; padding: 20px; margin-bottom: 40px;"  href="' . $siteUrl . 'index.php?route=product/product&product_id=' . $product['product_id'] . '"><span style="color: #ffffff; text-shadow: 0 -1px 0 rgb(0 0 0 / 25%); background-color: #da4f49; border-color: #da4f49; padding: 10px; box-shadow: inset 0 1px 0 rgb(255 255 255 / 20%), 0 1px 2px rgb(0 0 0 / 50%);">' . $product['name'] . '<span></a>' . "\n";


						if ($this->config->get('config_telegram_newsletters')) {
							$botApiToken = $this->config->get(config_telegram_token);
							$chat_id =  $this->config->get('config_telegram_chat_id');
							$text = $this->config->get('config_telegram_message'). "\n" . $siteUrl . 'index.php?route=product/product&product_id=' . $product['product_id'];
		
							$data = [
								'chat_id' => $chat_id,
								'text' => $text
							];
		
							file_get_contents("https://api.telegram.org/bot{$botApiToken}/sendMessage?" . http_build_query($data) );
		
							// $data = [
							// 	'chat_id' => $chat_id,
							// 	'photo'     => $this->model_tool_image->resize($product['image'], 500, 375)
							// ];
							// file_get_contents("https://api.telegram.org/bot{$botApiToken}/sendPhoto?" . http_build_query($data) );
						}
					}
				}
				
				if ($messageStr == '') return;


				$this->model_setting_setting->editSetting('last_newsletter', array(
					'last_newsletter_product_id' => (int)$last_newsletter_product_id
				));


				// $message .= html_entity_decode($messageStr, ENT_QUOTES, 'UTF-8');
				$message .= $messageStr;

				$message .= '    </div>' . "\n";
				$message .= '  </body>' . "\n";

				$message .= '</html>' . "\n";

				

				// echo '<pre>' .htmlspecialchars( $message ) . '</pre>';
				// echo $message;
				// exit;
				

				foreach ($emails as $email) {
					if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
						$mail = new Mail();
						$mail->protocol = $this->config->get('config_mail_protocol');
						$mail->parameter = $this->config->get('config_mail_parameter');
						$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
						$mail->smtp_username = $this->config->get('config_mail_smtp_username');
						$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
						$mail->smtp_port = $this->config->get('config_mail_smtp_port');
						$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

						$mail->setTo($email);
						$mail->setFrom($store_email);
						$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
						$mail->setSubject(html_entity_decode('?????????????? ???? ?????????? ' . ucfirst($store_name), ENT_QUOTES, 'UTF-8'));
						$mail->setHtml($message);
						$mail->send();
					}
				}

				echo 'Done';
			}

        } 
	}

	public function send() {
		$this->load->language('marketing/contact');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'marketing/contact')) {
				$json['error']['warning'] = $this->language->get('error_permission');
			}

			if (!$this->request->post['subject']) {
				$json['error']['subject'] = $this->language->get('error_subject');
			}

			if (!$this->request->post['message']) {
				$json['error']['message'] = $this->language->get('error_message');
			}

			if (!$json) {
				$this->load->model('setting/store');

				$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);

				if ($store_info) {
					$store_name = $store_info['name'];
				} else {
					$store_name = $this->config->get('config_name');
				}
				
				$this->load->model('setting/setting');
				$setting = $this->model_setting_setting->getSetting('config', $this->request->post['store_id']);
				$store_email = isset($setting['config_email']) ? $setting['config_email'] : $this->config->get('config_email');

				$this->load->model('customer/customer');

				$this->load->model('customer/customer_group');

				$this->load->model('marketing/affiliate');

				$this->load->model('sale/order');

				if (isset($this->request->get['page'])) {
					$page = $this->request->get['page'];
				} else {
					$page = 1;
				}

				$email_total = 0;

				$emails = array();

				switch ($this->request->post['to']) {
					// Start Newsletter Subscribers by Module
					case 'newsletter_all':
						$newsletter_data = array(
							'start' => ($page - 1) * 10,
							'limit' => 10
						);

						$email_total = $this->model_marketing_affiliate->getTotalNewsletters($newsletter_data);

						$results = $this->model_marketing_affiliate->getAllNewsletters($newsletter_data);

						foreach ($results as $result) {
							$emails[] = $result['news_email'];
						}
						break;
					// End Newsletter Subscribers by Module
					case 'newsletter':
						$customer_data = array(
							'filter_newsletter' => 1,
							'start'             => ($page - 1) * 10,
							'limit'             => 10
						);

						$email_total = $this->model_customer_customer->getTotalCustomers($customer_data);

						$results = $this->model_customer_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$emails[] = $result['email'];
						}
						break;
					case 'customer_all':
						$customer_data = array(
							'start' => ($page - 1) * 10,
							'limit' => 10
						);

						$email_total = $this->model_customer_customer->getTotalCustomers($customer_data);

						$results = $this->model_customer_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$emails[] = $result['email'];
						}
						break;
					case 'customer_group':
						$customer_data = array(
							'filter_customer_group_id' => $this->request->post['customer_group_id'],
							'start'                    => ($page - 1) * 10,
							'limit'                    => 10
						);

						$email_total = $this->model_customer_customer->getTotalCustomers($customer_data);

						$results = $this->model_customer_customer->getCustomers($customer_data);

						foreach ($results as $result) {
							$emails[$result['customer_id']] = $result['email'];
						}
						break;
					case 'customer':
						if (!empty($this->request->post['customer'])) {
							foreach ($this->request->post['customer'] as $customer_id) {
								$customer_info = $this->model_customer_customer->getCustomer($customer_id);

								if ($customer_info) {
									$emails[] = $customer_info['email'];
								}
							}
						}
						break;
					case 'affiliate_all':
						$affiliate_data = array(
							'start' => ($page - 1) * 10,
							'limit' => 10
						);

						$email_total = $this->model_marketing_affiliate->getTotalAffiliates($affiliate_data);

						$results = $this->model_marketing_affiliate->getAffiliates($affiliate_data);

						foreach ($results as $result) {
							$emails[] = $result['email'];
						}
						break;
					case 'affiliate':
						if (!empty($this->request->post['affiliate'])) {
							foreach ($this->request->post['affiliate'] as $affiliate_id) {
								$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);

								if ($affiliate_info) {
									$emails[] = $affiliate_info['email'];
								}
							}
						}
						break;
					case 'product':
						if (isset($this->request->post['product'])) {
							$email_total = $this->model_sale_order->getTotalEmailsByProductsOrdered($this->request->post['product']);

							$results = $this->model_sale_order->getEmailsByProductsOrdered($this->request->post['product'], ($page - 1) * 10, 10);

							foreach ($results as $result) {
								$emails[] = $result['email'];
							}
						}
						break;
				}

				if ($emails) {
					$json['success'] = $this->language->get('text_success');

					$start = ($page - 1) * 10;
					$end = $start + 10;

					if ($end < $email_total) {
						$json['success'] = sprintf($this->language->get('text_sent'), $start, $email_total);
					}

					if ($end < $email_total) {
						$json['next'] = str_replace('&amp;', '&', $this->url->link('marketing/contact/send', 'token=' . $this->session->data['token'] . '&page=' . ($page + 1), true));
					} else {
						$json['next'] = '';
					}

					$message  = '<html dir="ltr" lang="en">' . "\n";
					$message .= '  <head>' . "\n";
					$message .= '    <title>' . $this->request->post['subject'] . '</title>' . "\n";
					$message .= '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
					$message .= '  </head>' . "\n";
					$message .= '  <body>' . html_entity_decode($this->request->post['message'], ENT_QUOTES, 'UTF-8') . '</body>' . "\n";
					$message .= '</html>' . "\n";

					foreach ($emails as $email) {
						if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
							$mail = new Mail();
							$mail->protocol = $this->config->get('config_mail_protocol');
							$mail->parameter = $this->config->get('config_mail_parameter');
							$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
							$mail->smtp_username = $this->config->get('config_mail_smtp_username');
							$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
							$mail->smtp_port = $this->config->get('config_mail_smtp_port');
							$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

							$mail->setTo($email);
							$mail->setFrom($store_email);
							$mail->setSender(html_entity_decode($store_name, ENT_QUOTES, 'UTF-8'));
							$mail->setSubject(html_entity_decode($this->request->post['subject'], ENT_QUOTES, 'UTF-8'));
							$mail->setHtml($message);
							$mail->send();
						}
					}
				} else {
					$json['error']['email'] = $this->language->get('error_email');
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}