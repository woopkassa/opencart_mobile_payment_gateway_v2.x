<?php

class ControllerExtensionPaymentWooppayMobile extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('extension/payment/wooppayMobile');

		$this->document->setTitle = $this->language->get('heading_title');

		$this->load->model('setting/setting');
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			$this->load->model('setting/setting');
			$this->model_setting_setting->editSetting('wooppayMobile', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_liqpay'] = $this->language->get('text_liqpay');
		$data['text_card'] = $this->language->get('text_card');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		// wooppay ENTER
		$data['entry_merchant'] = $this->language->get('entry_merchant');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_url'] = $this->language->get('entry_url');
		$data['entry_prefix'] = $this->language->get('entry_prefix');
		$data['entry_service'] = $this->language->get('entry_service');

		// URL
		$data['copy_result_url'] = HTTP_CATALOG . 'index.php?route=extension/payment/wooppayMobile/callback';
		$data['copy_success_url'] = HTTP_CATALOG . 'index.php?route=extension/payment/wooppayMobile/success';

		$data['entry_success_status'] = $this->language->get('entry_success_status');
		$data['entry_processing_status'] = $this->language->get('entry_processing_status');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['url'])) {
			$data['error_url'] = $this->error['url'];
		} else {
			$data['error_url'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/wooppayMobile', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('extension/payment/wooppayMobile', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['wooppayMobile_merchant'])) {
			$data['wooppayMobile_merchant'] = $this->request->post['wooppayMobile_merchant'];
		} else {
			$data['wooppayMobile_merchant'] = $this->config->get('wooppayMobile_merchant');
		}
		if (isset($this->request->post['wooppayMobile_password'])) {
			$data['wooppayMobile_password'] = $this->request->post['wooppayMobile_password'];
		} else {
			$data['wooppayMobile_password'] = $this->config->get('wooppayMobile_password');
		}
		if (isset($this->request->post['wooppayMobile_url'])) {
			$data['wooppayMobile_url'] = $this->request->post['wooppayMobile_url'];
		} else {
			$data['wooppayMobile_url'] = $this->config->get('wooppayMobile_url');
		}
		if (isset($this->request->post['wooppayMobile_prefix'])) {
			$data['wooppayMobile_prefix'] = $this->request->post['wooppayMobile_prefix'];
		} else {
			$data['wooppayMobile_prefix'] = $this->config->get('wooppayMobile_prefix');
		}

		if (isset($this->request->post['wooppayMobile_service'])) {
			$data['wooppayMobile_service'] = $this->request->post['wooppayMobile_service'];
		} else {
			$data['wooppayMobile_service'] = $this->config->get('wooppayMobile_service');
		}

		if (isset($this->request->post['wooppayMobile_order_success_status_id'])) {
			$data['wooppayMobile_order_success_status_id'] = $this->request->post['wooppayMobile_order_success_status_id'];
		} else {
			$data['wooppayMobile_order_success_status_id'] = $this->config->get('wooppayMobile_order_success_status_id');
		}

		if (isset($this->request->post['wooppayMobile_order_processing_status_id'])) {
			$data['wooppayMobile_order_processing_status_id'] = $this->request->post['wooppayMobile_order_processing_status_id'];
		} else {
			$data['wooppayMobile_order_processing_status_id'] = $this->config->get('wooppayMobile_order_processing_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['wooppayMobile_status'])) {
			$data['wooppayMobile_status'] = $this->request->post['wooppayMobile_status'];
		} else {
			$data['wooppayMobile_status'] = $this->config->get('wooppayMobile_status');
		}

		if (isset($this->request->post['wooppayMobile_sort_order'])) {
			$data['wooppayMobile_sort_order'] = $this->request->post['wooppayMobile_sort_order'];
		} else {
			$data['wooppayMobile_sort_order'] = $this->config->get('wooppayMobile_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/wooppayMobile.tpl', $data));
	}

	private function validate()
	{
		if (!$this->user->hasPermission('modify', 'extension/payment/wooppayMobile')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['wooppayMobile_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['wooppayMobile_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['wooppayMobile_url']) {
			$this->error['url'] = $this->language->get('error_url');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function install() {
		$this->load->model('extension/payment/wooppayMobile');
		$this->model_extension_payment_wooppayMobile->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/wooppayMobile');
		$this->model_extension_payment_wooppayMobile->uninstall();
	}
}

?>