<?php

class Paymee_Paymeegateway_PaymentController extends Mage_Core_Controller_Front_Action {
	public function redirectAction() {
		$this->loadLayout();
        $block = $this->getLayout()->createBlock('Mage_Core_Block_Template','paymeegateway',array('template' => 'paymeegateway/redirect.phtml'));
		$this->getLayout()->getBlock('content')->append($block);
        $this->renderLayout();
	}
	
	public function responseAction() {
		header("content-type: application/json");
		if($this->getRequest()->isPost()) {
			$xApiKey = Mage::getConfig()->getNode('default/payment/paymeegateway/paymee_x_api_key');
			$xApiToken = Mage::getConfig()->getNode('default/payment/paymeegateway/paymee_x_api_token');
			if(empty($xApiKey) || empty($xApiToken)) {
				header('WWW-Authenticate: Basic realm="Callback Secret"');
				header('HTTP/1.0 401 Unauthorized');
				return;
			}
			$LoginSuccessful = false;
			if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
				$Username = $_SERVER['PHP_AUTH_USER'];
				$Password = $_SERVER['PHP_AUTH_PW'];
				if ($Username == $xApiKey && $Password == $xApiToken) {
					$LoginSuccessful = true;
				}
			}
			if (!$LoginSuccessful){
				header('WWW-Authenticate: Basic realm="Callback Secret PayMee"');
				header('HTTP/1.0 401 Unauthorized');
				return;
			}
			$data = json_decode(file_get_contents("php://input"));
			$validated = true;
			$orderId = $data->referenceCode;
			if($validated) {
				$order = Mage::getModel('sales/order');
				$order->loadByIncrementId($orderId);
				if(floatval($order->getGrandTotal()) === floatval($data->amount)) {
					$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true, 'Confirmação de pagamento PayMee Brasil no valor de ' + $data->amount);
					$order->sendNewOrderEmail();
					$order->setEmailSent(true);
					$order->save();
					Mage::getSingleton('checkout/session')->unsQuoteId();
					echo "{}";
				}
			}
			else {
				Mage_Core_Controller_Varien_Action::_redirect('');
			}
		}
		else
			Mage_Core_Controller_Varien_Action::_redirect('');
	}
}