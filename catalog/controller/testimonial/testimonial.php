<?php
class ControllerTestimonialTestimonial extends Controller
{
    private $error = array();
    private $moduleName 			= 'testimonial';
    private $moduleModel 			= 'model_extension_module_testimonial';
    private $moduleModelPath 		= 'extension/module/testimonial';
    private $modulePath 		    = 'testimonial/testimonial';
    private $moduleVersion 			= '1.4.2';

    public function index()
    {

        $this->document->addStyle('catalog/view/theme/default/stylesheet/testimonials.css');
        
        $lang_ar = $this->load->language($this->moduleModelPath);

        foreach($lang_ar as $key => $item){
            $data[$key] = $item;
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['heading_title'] = $this->language->get('heading_title');

        $this->document->setTitle($data['heading_title']);

        $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
        
        $data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));

        $this->load->model($this->moduleModelPath);

        $data['review_status'] = $this->config->get('config_review_status');

        if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
            $data['review_guest'] = true;
        } else {
            $data['review_guest'] = false;
        }

        if ($this->customer->isLogged()) {
            $data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
            $data['customer_email'] = $this->customer->getEmail();
        } else {
            $data['customer_name'] = '';
            $data['customer_email'] = '';
        }

        $data['breadcrumbs'][] = array(
            'text' => $data['heading_title'],
            'href' => $this->url->link($this->modulePath)
        );

        $data['review'] = 'index.php?route=' . $this->modulePath . '/review';
        $data['write'] = 'index.php?route=' . $this->modulePath . '/write';

        if(substr(VERSION, 0, 7) > '2.1.0.1'){
            if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('testimonial', (array)$this->config->get('config_captcha_page'))) {
                $data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
            } else {
                $data['captcha'] = '';
            }
        }else{
            if ($this->config->get('config_google_captcha_status')) {
                $this->document->addScript('https://www.google.com/recaptcha/api.js');
                $data['site_key'] = $this->config->get('config_google_captcha_public');
            } else {
                $data['site_key'] = '';
            }
        }

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        if(substr(VERSION, 0, 7) > '2.1.0.2'){
            $this->response->setOutput($this->load->view($this->modulePath, $data));
        }else{
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $this->modulePath . '.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/' . $this->modulePath . '.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/' . $this->modulePath . '.tpl', $data));
            }
        }
    }

    public function review() {
        $this->load->language($this->moduleModelPath);
        $this->load->model($this->moduleModelPath);

        $data['text_no_reviews'] = $this->language->get('text_no_reviews');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $data['reviews'] = array();

        $review_total = $this->{$this->moduleModel}->getTotalReviews();

        $results = $this->{$this->moduleModel}->getReviews(($page - 1) * 5, 5);

        $this->load->model('tool/image');

        foreach ($results as $result) {

            if ($result['image1']) {
				$result['popup_image1'] = '/image/review/' . $result['image1'];
				$result['image1'] = $this->model_tool_image->resizeWithoutWhiteBorders('./review/' . $result['image1'], 80, 80);				
			} else {
				$result['popup_image1'] = '';
			}

			if ($result['image2']) {
				$result['popup_image2'] = '/image/review/' . $result['image2'];
				$result['image2'] = $this->model_tool_image->resizeWithoutWhiteBorders('./review/' . $result['image2'], 80, 80);				
			} else {
				$result['popup_image2'] = '';
			}

			if ($result['image3']) {
				$result['popup_image3'] = '/image/review/' . $result['image3'];
				$result['image3'] = $this->model_tool_image->resizeWithoutWhiteBorders('./review/' . $result['image3'], 80, 80);				
			} else {
				$result['popup_image3'] = '';
			}

            $data['reviews'][] = array(
                'author'     => $result['author'],
                'city'     => $result['city'],
                'email'     => $result['email'],
                'image1'     => $result['image1'],
                'popup_image1'     => $result['popup_image1'],
                'image2'     => $result['image2'],
                'popup_image2'     => $result['popup_image2'],
                'image3'     => $result['image3'],
                'popup_image3'     => $result['popup_image3'],
                'text'       => nl2br($result['text']),
                'rating'     => (int)$result['rating'],
                'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
            );
        }

        $pagination = new Pagination();

        $pagination->total = $review_total;
        $pagination->page = $page;
        $pagination->limit = 5;
        $pagination->url = 'index.php?route=' . $this->modulePath . '/review&page={page}';

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

        if(substr(VERSION, 0, 7) > '2.1.0.2'){
            $this->response->setOutput($this->load->view($this->modulePath . '_list', $data));
        }else{
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/' . $this->modulePath . '_list.tpl')) {
                $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/' . $this->modulePath . '_list.tpl', $data));
            } else {
                $this->response->setOutput($this->load->view('default/template/' . $this->modulePath . '_list.tpl', $data));
            }
        }
    }

    public function write() {
        
        $this->load->language($this->moduleModelPath);

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            
            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
                $json['error'] = $this->language->get('error_name');
            }

            if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 3000)) {
                $json['error'] = $this->language->get('error_text');
            }
            if ((utf8_strlen($this->request->post['city']) < 3) || (utf8_strlen($this->request->post['city']) > 32)) {
                $json['error'] = $this->language->get('error_city');
            }
            if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match($this->config->get('config_mail_regexp'), $this->request->post['email'])) {
                $json['error'] = $this->language->get('error_email');
            }
            if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
                $json['error'] = $this->language->get('error_rating');
            }

            // Images Begin

			if ( !$_FILES['image1']['name'] && !$_FILES['image2']['name'] && !$_FILES['image3']['name'] ) {
				$json['error'] = "Добавьте хотя бы одны фотографию";
			} else {
				if ( $_FILES['image1']['name'] ) {
				$this->request->post['image1'] = $this->uploadReviewImage($_FILES['image1']['tmp_name'], $_FILES['image1']['name']);
			} else {
				$this->request->post['image1'] = '';
			}
			if ( $_FILES['image2']['name'] ) {
				$this->request->post['image2'] = $this->uploadReviewImage($_FILES['image2']['tmp_name'], $_FILES['image2']['name']);
			} else {
				$this->request->post['image2'] = '';
			}
			if ( $_FILES['image3']['name'] ) {
				$this->request->post['image3'] = $this->uploadReviewImage($_FILES['image3']['tmp_name'], $_FILES['image3']['name']);
			} else {
				$this->request->post['image3'] = '';
			}
			}
			
			

			// Images End

            if ($this->config->get('config_google_captcha_status')) {
                $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->config->get('config_google_captcha_secret')) . '&response=' . $this->request->post['g-recaptcha-response'] . '&remoteip=' . $this->request->server['REMOTE_ADDR']);

                $recaptcha = json_decode($recaptcha, true);

                if (!$recaptcha['success']) {
                    $json['error'] = $this->language->get('error_captcha');
                }
            }

            if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('testimonial', (array)$this->config->get('config_captcha_page'))) {
                $captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

                if ($captcha) {
                    $json['error'] = $captcha;
                }
            }

            if (!isset($json['error'])) {
                $this->load->model($this->moduleModelPath);
                $json['success'] = $this->language->get('text_success');

                $this->{$this->moduleModel}->addReview($this->request->post);
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    protected function uploadReviewImage ($tmpName, $name) {

		$size_img = getimagesize($tmpName); 
		$dest_img = imagecreatetruecolor($size_img[0], $size_img[1]); 
		
		
		if ($size_img[2]==2) $src_img = imagecreatefromjpeg($tmpName); 
		else if ($size_img[2]==1) $src_img = imagecreatefromgif($tmpName); 
		else if ($size_img[2]==3) $src_img = imagecreatefrompng($tmpName); 
		
		imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $size_img[0], $size_img[1], $size_img[0], $size_img[1]); 
		
			$uploaddir = DIR_IMAGE . 'review/'; 
			$tmp_name = explode(".", $name);

		$extension = end($tmp_name);
		
		$file_name = uniqid() . "-" .date("m.d.y-H:i:s") . "." .  $extension;
				
		$file = $uploaddir . basename($file_name);
	
			
	
		if ($size_img[2]==2) imagejpeg($dest_img, $file); 
		else if ($size_img[2]==1) imagegif($dest_img, $file); 
		else if ($size_img[2]==3) imagepng($dest_img, $file); 
		imagedestroy($dest_img); 
		imagedestroy($src_img);	

		return $file_name;
	}
}