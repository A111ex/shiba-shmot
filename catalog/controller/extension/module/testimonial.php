<?php

class ControllerExtensionModuleTestimonial extends Controller
{
    public function index($setting)
    {
        static $module = 0;
        $data['heading_title'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['title'], ENT_QUOTES, 'UTF-8');

        $data['button_all_text'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['all_text'], ENT_QUOTES, 'UTF-8');
        $data['layout'] = (int)$setting['layout_id'];

        $data['button_all'] = (int)$setting['button_all'];
        $data['keyword'] = $this->url->link('testimonial/testimonial');

        $this->load->model('extension/module/testimonial');

        $results = $this->model_extension_module_testimonial->getModuleReviews(0, $setting['limit'], $setting['order']);

        if ($results) {
            $this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
            $this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
            
            $this->load->model('tool/image');

            foreach ($results as $result) {                

                if ($result['image1']) {
                    $result['popup_image1'] = './image/review/' . $result['image1'];
                    $result['image1'] = $this->model_tool_image->resizeWithoutWhiteBorders('./review/' . $result['image1'], 120, 80);				
                } else {
                    $result['image1'] = '';
                    $result['popup_image1'] = '';
                }

                if ($result['image2']) {
                    $result['popup_image2'] = './image/review/' . $result['image2'];
                    $result['image2'] = $this->model_tool_image->resizeWithoutWhiteBorders('./review/' . $result['image2'], 120, 80);				
                } else {
                    $result['image2'] = '';
                    $result['popup_image2'] = '';
                }

                if ($result['image3']) {
                    $result['popup_image3'] = './image/review/' . $result['image3'];
                    $result['image3'] = $this->model_tool_image->resizeWithoutWhiteBorders('./review/' . $result['image3'], 120, 80);				
                } else {
                    $result['image3'] = '';
                    $result['popup_image3'] = '';
                }
        
                $data['reviews'][] = array(
                    'review_id' => $result['review_id'],
                    'text' => utf8_substr(strip_tags(html_entity_decode($result['text'], ENT_QUOTES, 'UTF-8')), 0, $setting['text_limit']) . '..',
                    'rating' => (int)$result['rating'],
                    'author' => $result['author'],
                    'city' => $result['city'],
                    'image1'     => $result['image1'],
                    'popup_image1'     => $result['popup_image1'],
                    'image2'     => $result['image2'],
                    'popup_image2'     => $result['popup_image2'],
                    'image3'     => $result['image3'],
                    'popup_image3'     => $result['popup_image3'],
                    'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                );
            }

            $data['module'] = 'sr' . $module++;
            
            return $this->load->view('extension/module/testimonial', $data);
        }
    }
}