<?php
class ControllerExtensionModuleVideobanner extends Controller {
	public function index($setting) {
		static $module = 0;

		if ($setting['video']) {
			$data['video'] = 'image/' . $setting['video'];
			$data['url'] = $setting['url'];

			$data['module'] = $module++;

			return $this->load->view('extension/module/videobanner', $data);
		}
		
	}
}