<?php

class Loginstore_Payu_PaymentController extends Mage_Core_Controller_Front_Action{
    
    public function indexAction(){
        
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function redirectAction(){
        
        $payme = Mage::getModel('payu/payu');
        $fields = $payme->getFormFields();
        
        var_dump($fields);
        
        exit();
    }
}

