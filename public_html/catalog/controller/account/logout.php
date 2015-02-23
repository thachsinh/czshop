<?php
class ControllerAccountLogout extends Controller {
	public function index() {
		if ($this->customer->isLogged()) {
			$this->event->trigger('pre.customer.logout');

			$this->customer->logout();
			$this->cart->clear();

			unset($this->session->data['wishlist']);
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_address']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['comment']);
			unset($this->session->data['order_id']);
			unset($this->session->data['coupon']);
			unset($this->session->data['reward']);
			unset($this->session->data['voucher']);
			unset($this->session->data['vouchers']);

			$this->event->trigger('post.customer.logout');
			$this->load->language('account/logout');
			$this->session->data['message']['success'] = strip_tags($this->language->get('text_message'));	
		}

		$this->response->redirect('/');
	}
}