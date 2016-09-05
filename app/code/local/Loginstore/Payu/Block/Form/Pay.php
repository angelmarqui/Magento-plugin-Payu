<?php

class Loginstore_Payu_Block_Form_Pay extends Mage_Payment_Block_Form_Ccsave{
    
    protected function _construct() {
        parent::_construct();
        $this->setTemplate('payu/form/payu.phtml');
    }
}

