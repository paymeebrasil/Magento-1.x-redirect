<?php
class Paymee_Paymeegateway_Model_Standard extends Mage_Payment_Model_Method_Abstract {
	protected $_code = 'paymeegateway';
	protected $_formBlockType = 'paymeegateway/form_custompaymentmethod';
	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = true;
	protected $_canUseForMultishipping  = false;
	
	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl('paymeegateway/payment/redirect', array('_secure' => false));
	}
}
?>