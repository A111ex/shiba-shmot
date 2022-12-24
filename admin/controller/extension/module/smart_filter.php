<?php
class ControllerExtensionModuleSmartFilter extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/smart_filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_option'] = $this->language->get('text_option');
		$data['text_atribute'] = $this->language->get('text_atribute');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('smart_filter', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/smart_filter', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/smart_filter', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->post['smart_filter_status'])) {
			$data['smart_filter_status'] = $this->request->post['smart_filter_status'];
		} else {
			$data['smart_filter_status'] = $this->config->get('smart_filter_status');
		}

		if (isset($this->request->post['smart_filter_price'])) {
			$data['smart_filter_price'] = $this->request->post['smart_filter_price'];
		} else {
			$data['smart_filter_price'] = $this->config->get('smart_filter_price');
		}

		if (isset($this->request->post['smart_filter_category'])) {
			$data['smart_filter_category'] = $this->request->post['smart_filter_category'];
		} else {
			$data['smart_filter_category'] = $this->config->get('smart_filter_category');
		}
		if (isset($this->request->post['smart_filter_manufacturer'])) {
			$data['smart_filter_manufacturer'] = $this->request->post['smart_filter_manufacturer'];
		} else {
			$data['smart_filter_manufacturer'] = $this->config->get('smart_filter_manufacturer');
		}


		if (isset($this->request->post['smart_filter_option'])) {
			$data['smart_filter_option'] = $this->request->post['smart_filter_option'];
		} else {
			$data['smart_filter_option'] = $this->config->get('smart_filter_option');
		}

		if (isset($this->request->post['smart_filter_atribute'])) {
			$data['smart_filter_atribute'] = $this->request->post['smart_filter_atribute'];
		} else {
			$data['smart_filter_atribute'] = $this->config->get('smart_filter_atribute');
		}
		
		


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/smart_filter', $data));
	}


	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/smart_filter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}