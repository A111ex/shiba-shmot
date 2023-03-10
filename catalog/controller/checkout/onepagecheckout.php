<?php

class ControllerCheckoutOnepagecheckout extends Controller
{
    public $errors = array();

    public function index()
    {
        // Validate cart has products and has stock.
        if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
            $this->response->redirect($this->url->link('checkout/cart'));
        }

        $this->document->addStyle('catalog/view/theme/default/stylesheet/onepagecheckout.css');

        // Validate minimum quantity requirements.
        $products = $this->cart->getProducts();
        $this->load->language('checkout/onepagecheckout');
        $data['text_login'] = $this->language->get('text_login');
        $data['text_notlogged'] = $this->language->get('text_notlogged');
        $data['text_customer'] = $this->language->get('text_customer');
        $data['text_cart'] = $this->language->get('text_cart');
        $data['text_firstname'] = $this->language->get('text_firstname');
        $data['text_lastname'] = $this->language->get('text_lastname');
        $data['text_middlename'] = $this->language->get('text_middlename');
        $data['text_telephone'] = $this->language->get('text_telephone');
        $data['text_email'] = $this->language->get('text_email');
        $data['text_town'] = $this->language->get('text_town');
        $data['text_postcode'] = $this->language->get('text_postcode');
        $data['text_delivery_method'] = $this->language->get('text_delivery_method');
        $data['text_delivery_type_1'] = $this->language->get('text_delivery_type_1');
        $data['text_delivery_type_2'] = $this->language->get('text_delivery_type_2');
        $data['text_delivery_placeholder'] = $this->language->get('text_delivery_placeholder');
        $data['text_payment_method'] = $this->language->get('text_payment_method');
        $data['text_comment'] = $this->language->get('text_comment');
        $data['text_confirm'] = $this->language->get('text_confirm');
        $data['text_product'] = $this->language->get('text_product');
        $data['text_price'] = $this->language->get('text_price');
        $data['text_quantity'] = $this->language->get('text_quantity');
        $data['text_total'] = $this->language->get('text_total');
        $data['text_select'] = $this->language->get('text_select');
        $data['cart_total'] = 0;




        /* login translate*/


        $this->load->language('account/login');
        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_new_customer'] = $this->language->get('text_new_customer');
        $data['text_register'] = $this->language->get('text_register');
        $data['text_register_account'] = $this->language->get('text_register_account');
        $data['text_returning_customer'] = $this->language->get('text_returning_customer');
        $data['text_i_am_returning_customer'] = $this->language->get('text_i_am_returning_customer');
        $data['text_forgotten'] = $this->language->get('text_forgotten');

        $data['entry_email'] = $this->language->get('entry_email');
        $data['entry_password'] = $this->language->get('entry_password');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_login'] = $this->language->get('button_login');

        $data['action'] = $this->url->link('account/login', '', true);
        $data['register'] = $this->url->link('account/register', '', true);
        $data['forgotten'] = $this->url->link('account/forgotten', '', true);
        /*  if ($this->customer->isLogged()) {
              $this->response->redirect($this->url->link('account/account', '', true));
          }*/
        /* login translate END*/

        $this->load->model('tool/image');


        foreach ($products as $i => $product) {

            if(!empty($product['image'])){
                $products[$i]['thumb'] = $this->model_tool_image->resize($product['image'], $this->config->get($this->config->get('config_theme') . '_image_category_width'), $this->config->get($this->config->get('config_theme') . '_image_category_height'));
            } else {
                $products[$i]['thumb'] = '';
            }


            if($this->request->server['REQUEST_METHOD'] != 'POST')
                $products[$i]['price'] = $this->currency->format($product['price'], $this->session->data['currency']);
            else
                $products[$i]['price'] = $product['price'];
            $product_total = 0;
            $data['cart_total'] += $product['total'];
            $option_data = array();

            foreach ($product['option'] as $option) {
                $option_data[] = array(
                    'product_option_id' => $option['product_option_id'],
                    'product_option_value_id' => $option['product_option_value_id'],
                    'option_id' => $option['option_id'],
                    'option_value_id' => $option['option_value_id'],
                    'name' => $option['name'],
                    'value' => $option['value'],
                    'type' => $option['type']
                );
            }
            foreach ($products as $product_2) {
                if ($product_2['product_id'] == $product['product_id']) {
                    $product_total += $product_2['quantity'];
                }
            }

            if ($product['minimum'] > $product_total) {
                $this->response->redirect($this->url->link('checkout/cart'));
            }
        }

        // Totals
        $this->load->model('extension/extension');

        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;
        $total_val = 0;
        // Because __call can not keep var references so we put them into an array.
        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );

        // Display prices
        if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
            $sort_order = array();

            $results = $this->model_extension_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('extension/total/' . $result['code']);

                    // We have to put the totals in an array so that they pass by reference.
                    $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
                }
            }

            $sort_order = array();
            foreach ($totals as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $totals);
        }

        $data['totals'] = array();
        foreach ($totals as $total) {
            $data['totals'][] = array(
                'title' => $total['title'],
                'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
            );
            $total_val += $total['value'];
        }


        $data['products'] = $products;
        // $total_val = $data['cart_total'];
        $total_val = $total_data['total'];
        $data['cart_total'] = $this->currency->format($data['cart_total'], $this->session->data['currency']);

        // Gift Voucher
        $data['vouchers'] = array();

        if (!empty($this->session->data['vouchers'])) {
            foreach ($this->session->data['vouchers'] as $voucher) {
                $data['vouchers'][] = array(
                    'description' => $voucher['description'],
                    'code' => token(10),
                    'to_name' => $voucher['to_name'],
                    'to_email' => $voucher['to_email'],
                    'from_name' => $voucher['from_name'],
                    'from_email' => $voucher['from_email'],
                    'voucher_theme_id' => $voucher['voucher_theme_id'],
                    'message' => $voucher['message'],
                    'amount' => $voucher['amount']
                );
            }
        }


        $this->load->language('checkout/checkout');
        $data['entry_firstname'] = $this->language->get('entry_firstname');
        $data['entry_middlename'] = $this->language->get('entry_middlename');
        $data['entry_lastname'] = $this->language->get('entry_lastname');

        $data['entry_country'] = $this->language->get('entry_country');
        $data['entry_zone'] = $this->language->get('entry_zone');
        
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_cart'),
            'href' => $this->url->link('checkout/cart')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('checkout/checkout', '', true)
        );

        $data['heading_title'] = $this->language->get('heading_title');


        if (isset($this->session->data['error'])) {
            $data['error_warning'] = $this->session->data['error'];
            unset($this->session->data['error']);
        } else {
            $data['error_warning'] = '';
        }

        if ( $this->customer->isLogged()) {
            $this->load->model('account/address');
            $this->load->model('account/customer');
            $addr = $this->model_account_address->getAddress($this->customer->getAddressId());
            $data['c_logged'] = true;
            $data['firstname'] = $this->customer->getFirstName();
            $data['middlename'] = $this->customer->getMiddleName();
            $data['lastname'] = $this->customer->getLastName();
            $data['city'] = $addr['city'];
            $data['postcode_fastorder'] = $addr['postcode'];
            $data['address_1'] = $addr['address_1'];
            $data['email'] = $this->customer->getEmail();
            $data['telephone'] = $this->customer->getTelephone();
            $data['country_id'] = $addr['country_id'];
            $data['zone_id'] = $addr['zone_id'];

            $customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
			$data['account_custom_field'] = json_decode($customer_info['custom_field'], true);
        }
        else {
            $data['c_logged'] = false;
            $data['firstname'] = '';
            $data['middlename'] = '';
            $data['lastname'] = '';
            $data['city'] = '';
            $data['email'] = '';
            $data['postcode_fastorder'] = '';
            $data['address_1'] = '';
            $data['email'] = '';
            $data['telephone'] = '';
            $data['country_id'] = '';
            $data['zone_id'] = '';
        }

        $this->load->model('localisation/country');

        $data['countries'] = $this->model_localisation_country->getCountries();
        
        // Custom Fields
        $this->load->model('account/custom_field');

        $data['custom_fields'] = $this->model_account_custom_field->getCustomFields();

        if (isset($this->session->data['account'])) {
            $data['account'] = $this->session->data['account'];
        } else {
            $data['account'] = '';
        }

        if (isset($this->session->data['payment_address']['firstname'])) {
            $data['firstname'] = $this->session->data['payment_address']['firstname'];
        }

        if (isset($this->session->data['payment_address']['middlename'])) {
            $data['middlename'] = $this->session->data['payment_address']['middlename'];
        }

        if (isset($this->session->data['payment_address']['lastname'])) {
            $data['lastname'] = $this->session->data['payment_address']['lastname'];
        } 

        if (isset($this->session->data['payment_address']['address_1'])) {
            $data['address_1'] = $this->session->data['payment_address']['address_1']; //nomer otdelenia ili adres poluchatelya
        }

        if (isset($this->session->data['payment_address']['city'])) {
            $data['city'] = $this->session->data['payment_address']['city'];
        }
        if (isset($this->session->data['payment_address']['postcode'])) {
            $data['postcode_fastorder'] = $this->session->data['payment_address']['postcode'];
        }
        if (isset($this->session->data['payment_address']['telephone'])) {
            $data['telephone'] = $this->session->data['payment_address']['telephone'];
        }
        if (isset($this->session->data['comment'])) {
            $data['comment'] = $this->session->data['comment'];
        } else {
            $data['comment'] = '';
        }
        if (isset($this->session->data['email'])) {
            $data['email'] = $this->session->data['email'];
        }

        if (isset($this->session->data['address_1'])) {
            $data['address_1'] = $this->session->data['address_1'];
        }

        if (isset($this->session->data['country_id'])) {
            $data['country_id'] = $this->session->data['country_id'];
        } 

        if (isset($this->session->data['zone_id'])) {
            $data['zone_id'] =$this->session->data['zone_id'];
        } 


        //    var_dump(json_decode('{"type": "page", "id": 1, "color": "#69F"}',true));exit;
        $this->errors = [];
        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            

            if ($this->validate_form()) {
                $order_data = array();
                if ($this->affiliate->isLogged()) {
                    $order_data['affiliate_id'] = $this->affiliate->getId();
                } else {
                    $order_data['affiliate_id'] = '';

                }
                $order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
                $order_data['store_id'] = $this->config->get('config_store_id');
                $order_data['store_name'] = $this->config->get('config_name');

                if ($order_data['store_id']) {
                    $order_data['store_url'] = $this->config->get('config_url');
                } else {
                    if ($this->request->server['HTTPS']) {
                        $order_data['store_url'] = HTTPS_SERVER;
                    } else {
                        $order_data['store_url'] = HTTP_SERVER;
                    }
                }
                $order_data['products'] = $data['products'];
                $order_data['vouchers'] = $data['vouchers'];
                $order_data['cart_total'] = $total_val;

                if (isset($this->request->post['firstname'])) {
                    $this->session->data['payment_address']['firstname'] = $this->request->post['firstname'];
                    $order_data['firstname'] = $this->request->post['firstname'];
                }

                if (isset($this->request->post['middlename'])) {
                    $this->session->data['payment_address']['middlename'] = $this->request->post['middlename'];
                    $order_data['middlename'] = $this->request->post['middlename'];
                }

                if (isset($this->request->post['lastname'])) {
                    $this->session->data['payment_address']['lastname'] = $this->request->post['lastname'];
                    $order_data['lastname'] = $this->request->post['lastname'];
                }

                if (isset($this->request->post['telephone'])) {
                    $this->session->data['payment_address']['telephone'] = $this->request->post['telephone'];
                    $order_data['telephone'] = $this->request->post['telephone'];
                }

                if (isset($this->request->post['email'])) {
                    $this->session->data['payment_address']['email'] = $this->request->post['email'];
                    $order_data['email'] = $this->request->post['email'];
                    if(!empty(trim($this->request->post['email'])))
                        $order_data['order_status_id'] = 0 ;
                    else
                        $order_data['order_status_id'] = $this->config->get('config_order_status_id');
                }

                if (isset($this->request->post['city'])) {
                    $this->session->data['payment_address']['city'] = $this->request->post['city'];
                    $order_data['city'] = $this->request->post['city'];
                }              
                
                             
                
                if (isset($this->request->post['country_id'])) {
                    $this->session->data['payment_address']['country_id'] = $this->request->post['country_id'];
                    $order_data['country_id'] = $this->request->post['country_id'];

                    $this->load->model('localisation/country');
                    $country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

                    if ($country_info) {
                        $order_data['country'] = $country_info['name'];
                    } else {
                        $order_data['country'] = '';
                    }  
                }

                if (isset($this->request->post['zone_id'])) {
                    $this->session->data['payment_address']['zone_id'] = $this->request->post['zone_id'];
                    $order_data['zone_id'] = $this->request->post['zone_id'];

                    $this->load->model('localisation/zone');
                    $zone_info = $this->model_localisation_zone->getZone($this->request->post['zone_id']);
                    
                    if ($zone_info) {
                        $order_data['zone'] = $zone_info['name'];
                    } else {
                        $order_data['zone'] = '';
                    }
                }


                


                if (isset($this->request->post['postcode_fastorder'])) {
                    $this->session->data['payment_address']['postcode'] = $this->request->post['postcode_fastorder'];
                    $this->session->data['shipping_address']['postcode'] = $this->request->post['postcode_fastorder'];
                    $order_data['postcode'] = $this->request->post['postcode_fastorder'];
                }

                if (isset($this->request->post['shipping_method'])) {
                    $this->session->data['shipping_method'] = json_decode(htmlspecialchars_decode($this->request->post['shipping_method']), true);
                    $order_data['shipping_method'] = json_decode(htmlspecialchars_decode($this->request->post['shipping_method']), true);
                }

                //var_dump( $order_data); exit;

                if (isset($this->request->post['address_1'])) {
                    $this->session->data['payment_address']['address_1'] = $this->request->post['address_1'];
                    $order_data['address_1'] = $this->request->post['address_1'];
                }
                //   var_dump( json_encode(['title'=>'title','val'=>'val']));exit;


                if (isset($this->request->post['payment_method'])) {
                    $this->session->data['payment_method'] = json_decode(htmlspecialchars_decode($this->request->post['payment_method']), true);
                    $order_data['payment_method'] = json_decode(htmlspecialchars_decode($this->request->post['payment_method']), true);
                }

                if (isset($this->request->post['firstname'])) {
                    $this->session->data['firstname'] = $this->request->post['firstname'];
                }
                
                if (isset($this->request->post['middlename'])) {
                    $this->session->data['middlename'] = $this->request->post['middlename'];
                }
                
                if (isset($this->request->post['lastname'])) {
                    $this->session->data['lastname'] = $this->request->post['lastname'];
                }

                if (isset($this->request->post['comment'])) {
                    $this->session->data['comment'] = $this->request->post['comment'];
                    $order_data['comment'] = $this->request->post['comment'];
                }
                if (isset($this->request->post['delivery-type'])) {
                    $this->session->data['delivery-type'] = $this->request->post['delivery-type'];
                    $order_data['address_1'] = $this->request->post['delivery-type'] . ' - ' . $order_data['address_1'];
                }


                $order_data['language_id'] = $this->config->get('config_language_id');
                $order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
                $order_data['currency_code'] = $this->session->data['currency'];
                $order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
                $order_data['ip'] = $this->request->server['REMOTE_ADDR'];

                if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                    $order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
                } elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                    $order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
                } else {
                    $order_data['forwarded_ip'] = '';
                }

                if (isset($this->request->server['HTTP_USER_AGENT'])) {
                    $order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
                } else {
                    $order_data['user_agent'] = '';
                }

                if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
                    $order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
                } else {
                    $order_data['accept_language'] = '';
                }

                
                $order_data['customer_id'] = $this->customer->getId();

                if (!$order_data['customer_id']) {

                    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                    $order_data['password'] =  substr(str_shuffle($chars),0,8);

                    $order_data['fax'] = '';                    
                    $order_data['company'] = '';                    
                    $order_data['address_2'] = '';                    

                    if (isset($this->request->post['custom_field'])) {
                        $order_data['custom_field']['account'] = $this->request->post['custom_field']['account']; // for add customer
                    } 

                    $order_data['customer_id'] = $this->model_account_customer->addCustomer($order_data);
                    unset($order_data['custom_field']['account']); // for add order                    
                }

                if (isset($this->request->post['custom_field'])) {                        
                    $order_data['custom_field'] = $this->request->post['custom_field']['account']; // for add order
                }                
                
                if (isset($this->session->data['guest']['customer_group_id'])) {
                    $order_data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
                } else {
                    $order_data['customer_group_id'] = $this->config->get('config_customer_group_id');
                }


                $this->load->model('checkout/onepagecheckout');
                $json['order_id'] = $this->model_checkout_onepagecheckout->addOrder($order_data);
                $this->load->model('checkout/order');
                $this->model_checkout_order->addOrderHistory($json['order_id'], $this->config->get('config_order_status_id'), '', 0, 0);

                $this->session->data['order_id'] = $json['order_id'];
                // $json['order_id']=$this->addOrder($order_data);
                // var_dump($this->session->data['payment_method']);exit;

                $json['payment'] = $this->load->controller('extension/payment/' . $this->session->data['payment_method']['code']);
                if ($this->session->data['payment_method']['code']=='cod'){
                    $json['cod'] = 1;
                }

                $this->session->data['account'] = 'register';
                $this->load->model('account/customer_group');
                
                $customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->config->get('config_customer_group_id'));


                if ($customer_group_info && !$customer_group_info['approval'] && !$this->customer->isLogged()) {

                    if (!$this->customer->login($this->request->post['email'], $order_data['password'])) {

                        $this->errors['warning'] = $this->language->get('error_login');            
                        $this->model_account_customer->addLoginAttempt($this->request->post['email']);

                        // Add to activity log
                        if ($this->config->get('config_customer_activity')) {
                            $this->load->model('account/activity');

                            $activity_data = array(
                                'customer_id' => $order_data['customer_id'],
                                'name'        => $this->request->post['lastname'] . ' ' . $this->request->post['firstname'] . ' ' . $this->request->post['middlename']
                            );

                            $this->model_account_activity->addActivity('register', $activity_data);
                        }

                    } else {
                        $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
                    }                     
                } 
                    
            } else
                $json['error'] = $this->errors;

            $this->response->addHeader('Content-Type: application/json');
            $this->response->setOutput(json_encode($json));
        } else {

            $this->session->data['shipping_address']['country_id'] = 0;
            $this->session->data['shipping_address']['zone_id'] = 0;
            
            
            /* payment methods*/
            // Payment Methods
            // Totals
            $totals = array();
            $taxes = $this->cart->getTaxes();
            $total = 0;

            // Because __call can not keep var references so we put them into an array.
            $total_data = array(
                'totals' => &$totals,
                'taxes' => &$taxes,
                'total' => &$total
            );

            $this->load->model('extension/extension');

            $sort_order = array();

            $results = $this->model_extension_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('extension/total/' . $result['code']);

                    // We have to put the totals in an array so that they pass by reference.
                    $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
                }
            }


            $data['modules'] = array();

            $files = glob(DIR_APPLICATION . '/controller/extension/total/*.php');

            if ($files) {
                foreach ($files as $file) {
                    $result = $this->load->controller('extension/total/' . basename($file, '.php'));

                    if ($result) {
                        $data['modules'][] = $result;
                    }
                }
            }


            $method_data = array();
            $this->session->data['payment_address']['country_id'] = 0;
            $this->session->data['payment_address']['zone_id'] = 0;
            $this->load->model('extension/extension');

            $results = $this->model_extension_extension->getExtensions('payment');

            $recurring = $this->cart->hasRecurringProducts();

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('extension/payment/' . $result['code']);

                    $method = $this->{'model_extension_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);

                    if ($method) {
                        if ($recurring) {
                            if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
                                $method_data[$result['code']] = $method;
                            }
                        } else {
                            $method_data[$result['code']] = $method;
                        }
                    }
                }
            }

            $sort_order = array();

            foreach ($method_data as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $method_data);


            $this->session->data['payment_methods'] = $method_data;


            $data['payment_methods'] = $method_data;


            $data['column_left'] = $this->load->controller('common/column_left');
            $data['column_right'] = $this->load->controller('common/column_right');
            $data['content_top'] = $this->load->controller('common/content_top');
            $data['content_bottom'] = $this->load->controller('common/content_bottom');
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');

            $this->response->setOutput($this->load->view('checkout/onepagecheckout', $data));
        }

    }



    public function validate_form()
    {
        // print_r($this->request->post);exit;
        $this->error = [];

        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {

            $data['error']['firstname'] = $this->language->get('error_firstname');
        }
        
        if ((utf8_strlen(trim($this->request->post['middlename'])) < 1) || (utf8_strlen(trim($this->request->post['middlename'])) > 32)) {

            $data['error']['middlename'] = $this->language->get('error_middlename');
        }
        
        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {

            $data['error']['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen(trim($this->request->post['telephone'])) < 5) || (utf8_strlen(trim($this->request->post['telephone'])) > 16)) {
            //var_dump($this->request->post['telephone']);exit;
            $data['error']['telephone'] = $this->language->get('error_telephone_fastorder');

        }

        if ((utf8_strlen(trim($this->request->post['address_1'])) < 1) || (utf8_strlen(trim($this->request->post['address_1'])) > 92)) {
            $data['error']['address_1'] = $this->language->get('error_address_1');
        }
        
        if ((utf8_strlen(trim($this->request->post['city'])) < 1) || (utf8_strlen(trim($this->request->post['city'])) > 32)) {
            $data['error']['city'] = $this->language->get('error_city');
        }

        if ((utf8_strlen(trim($this->request->post['postcode_fastorder'])) != 6)) {
            $data['error']['postcode'] = $this->language->get('error_postcode_fastorder');
        }

        if ($this->request->post['country_id'] == '') {
            $data['error']['country'] = $this->language->get('error_country');
		}

		if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '' || !is_numeric($this->request->post['zone_id'])) {
            $data['error']['zone'] = $this->language->get('error_zone');
        }
        
		if (!isset($this->request->post['shipping_method']) || $this->request->post['shipping_method'] == '' ) {
            $data['error']['shipping_method'] = $this->language->get('error_shipping_method');
        }
        

        if ( $this->request->post['email'] !== '' && filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL) ) {

            $this->load->model('account/customer');
            $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

            if ( $customer_info && $customer_info['approved'] && $customer_info['customer_id'] !== $this->customer->getId() ) {
                $data['error']['email'] = $this->language->get('error_email_already_used');
            }

        } else {
            $data['error']['email'] = $this->language->get('error_email');
        }


        if (!empty($data['error'])) {
            $this->errors = $data['error'];
            return false;
        } else
            return true;
    }


    public function AjaxLogin(){
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateLogin()) {
            $this->load->model('account/address');
            $addr = $this->model_account_address->getAddress($this->customer->getAddressId());
            $loginData['firstname'] = $this->customer->getFirstName();
            $loginData['middlename'] = $this->customer->getMiddleName();
            $loginData['lastname'] = $this->customer->getLastName();
            $loginData['city'] =$addr['city'];
            $loginData['postcode'] =$addr['postcode'];
            $loginData['address_1'] = $addr['address_1'];
            $loginData['email'] = $this->customer->getEmail();
            $loginData['telephone'] = $this->customer->getTelephone();
            $this->load->language('account/login');
            // Unset guest
            unset($this->session->data['guest']);

            // Default Shipping Address
            $this->load->model('account/address');

            if ($this->config->get('config_tax_customer') == 'payment') {
                $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            if ($this->config->get('config_tax_customer') == 'shipping') {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }



            // Add to activity log
            if ($this->config->get('config_customer_activity')) {
                $this->load->model('account/activity');

                $activity_data = array(
                    'customer_id' => $this->customer->getId(),
                    'name'        => $this->customer->getLastName() . ' ' . $this->customer->getFirstName() . ' ' . $this->customer->getMiddleName()
                );

                $this->model_account_activity->addActivity('login', $activity_data);
            }


        }
        if( $this->errors)
            $loginData['errors'] = $this->errors;
        else
            $loginData['errors']=0;
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($loginData));
    }


    protected function validateLogin() {
        // Check how many login attempts have been made.
        $this->load->model('account/customer');
        $this->load->language('account/login');
        $login_info = $this->model_account_customer->getLoginAttempts($this->request->post['email']);

        if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
            $this->errors['warning'] = $this->language->get('error_attempts');
        }

        // Check if customer has been approved.
        $customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

        if ($customer_info && !$customer_info['approved']) {
            $this->errors['warning'] = $this->language->get('error_approved');
        }

        if (!$this->errors) {
            if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
                $this->errors['warning'] = $this->language->get('error_login');

                $this->model_account_customer->addLoginAttempt($this->request->post['email']);
            } else {
                $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
            }
        } 
        return  !$this->errors;
    }



    public function getShippingMethods() {

        $this->session->data['shipping_address']['postcode'] = $this->request->post['postcode_fastorder'];

        // Shipping Methods BEGIN
        $method_data = array();

        $this->load->model('extension/extension');

        $results = $this->model_extension_extension->getExtensions('shipping');

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('extension/shipping/' . $result['code']);

                $method_data[$result['code']] = $this->{'model_extension_shipping_' . $result['code']}->getQuote($this->session->data['shipping_address']);

            }
        }

        $sort_order = array();

        foreach ($method_data as $key => $value) {
            $sort_order[$key] = $value['sort_order'];
        }

        array_multisort($sort_order, SORT_ASC, $method_data);

        $this->session->data['shipping_methods'] = $method_data;


        foreach ($method_data as $i => $shipping_method)
            foreach ($shipping_method['quote'] as $shipping_method2) {
                $data['shipping_methods'][$i][$shipping_method2['code']]['value'] = $shipping_method2['code'];
                $data['shipping_methods'][$i][$shipping_method2['code']]['title'] = $shipping_method2['title'];
                $data['shipping_methods'][$i][$shipping_method2['code']]['class'] = substr($shipping_method2['code'], strpos($shipping_method2['code'], '.')+1 );
                if (isset($shipping_method2['cost']))
                    $data['shipping_methods'][$i][$shipping_method2['code']]['cost'] = $shipping_method2['cost'];
                else
                    $data['shipping_methods'][$i][$shipping_method2['code']]['cost']='';

            }
        // Shipping Methods END

        if (!$data){
            $data = array('error' => '?????? ???????????????????? ???????????? ?????? ?????????????????????? ?????????????? ????????????????');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));

    }



    public function totals() {
        $this->load->model('extension/extension');

        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;
        $total_val = 0;
        // Because __call can not keep var references so we put them into an array. 			
        $total_data = array(
            'totals' => &$totals,
            'taxes'  => &$taxes,
            'total'  => &$total
        );

        // Display prices
        if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
            $sort_order = array();

            $results = $this->model_extension_extension->getExtensions('total');

            foreach ($results as $key => $value) {
                $sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
            }

            array_multisort($sort_order, SORT_ASC, $results);

            foreach ($results as $result) {
                if ($this->config->get($result['code'] . '_status')) {
                    $this->load->model('extension/total/' . $result['code']);

                    // We have to put the totals in an array so that they pass by reference.
                    $this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
                }
            }

            $sort_order = array();
            foreach ($totals as $key => $value) {
                $sort_order[$key] = $value['sort_order'];
            }

            array_multisort($sort_order, SORT_ASC, $totals);
        }

        $data['totals'] = array();
        foreach ($totals as $total) {
            $data['totals'][] = array(
                'title' => $total['title'],
                'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
            );
            $total_val += $total['value'];
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));

    }

}
