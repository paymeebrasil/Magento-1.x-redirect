<?php
class Paymee_Paymeegateway_Block_Form_Custompaymentmethod extends Mage_Payment_Block_Form
{
  protected function _construct()
  {
    parent::_construct();
    $this->setTemplate('paymeegateway/form/custompaymentmethod.phtml');
  }
}