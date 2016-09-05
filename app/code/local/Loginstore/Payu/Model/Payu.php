<?php

class Loginstore_Payu_Model_Payu extends Mage_Payment_Model_Method_Abstract {

    protected $_code = 'payu';
    protected $_canUseForMultishipping = false;
    protected $_formBlockType = 'payu/form_pay';
    protected $_infoBlockType = 'payu/info_pay';
    protected $_isGateway = true;
    protected $_canAuthorize = true;
    protected $_canCapture = true;
    //protected $_canCapturePartial       = true;
    protected $_canRefund = false;
    protected $_canSaveCc = false; //if made try, the actual credit card number and cvv code are stored in database.

    public function getOrderPlaceRedirectUlr() {
        return Mage::getUrl('payu/payment/redirect', array('_secure' => true));
    }

    public function getFormFields() {

        $acquirer_id = Mage::getStoreConfig('payment/payme/acquirer_id');
        $idCommerce = Mage::getStoreConfig('payment/payme/IDCOMMERCE');

        $customer = Mage::getSingleton('customer/session');

        $checkout = Mage::getSingleton('checkout/session');
        $orderIncrementId = $checkout->getLastRealOrderId();
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
        $cart = Mage::getSingleton('checkout/cart');

        echo('<b>ORDER </b>');
        $m = get_class_methods($order);
        foreach ($m as $m1) {
            echo($m1 . '<br />');
        }

        $lastOrderId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
//        echo ($lastOrderId.'<br />');
        $currency = $order->getOrderCurrency();
        echo ($currency->getCode() . '<br />');
        echo ($currency->getCurrencyCode() . '<br />');

        $purchaseCurrentCode = '604';
        if ($currency === 'PEN') {
            $purchaseCurrentCode = '604';
        }
        if ($currency === 'USD') {
            $purchaseCurrentCode = '840';
        }
        if ($currency === 'EUR') {
            $purchaseCurrentCode = '604';
        }


        var_dump(get_class_methods($order->getOrderCurrency()));


        // Billing

        $billingAddress = $order->getBillingAddress();
        $nameBilling = split(' ', $billingAddress->getName());

        $firstnameBilling = $nameBilling[0];
        $lastnameBilling  = $nameBilling[1];
        $emailBilling     = $order->getCustomerEmail();
        $addressBilling   = $billingAddress->getStreetFull();
        $zipBilling       = '051';
        $cityBilling      = $billingAddress->getRegion();
        $stateBilling     = $billingAddress->getRegion();
        $countryBilling   = $billingAddress->getCountry();

        // Shipping
        $shippingAddress = $order->getShippingAddress();
        $nameShipping    = split(' ', $shippingAddress->getName());

        $firstnameShipping = $nameShipping[0];
        $lastnameShipping  = $nameShipping[1];
        $emailShipping     = $order->getCustomerEmail();
        $addressShipping   = $shippingAddress->getStreetFull();
        $zipShipping       = '051';
        $cityShipping      = $shippingAddress->getRegion();
        $stateShipping     = $shippingAddress->getRegion();
        $countryShipping   = $shippingAddress->getCountry();

        // User Code

        $userCommerce = $customer->getId();

        // User Code Payme
        $userCodePayme = '';

        // Products
        $descProductos = '';
        $items = $order->getAllItems();
        if ($items) {
            foreach ($items as $item) {
                if ($item->getParentItem())
                    continue;
                $descProductos .= $item->getName() . '; ';
            }
        }
        $descProductos = rtrim($descProductos, '; ');
        $paymentAmount = number_format($order->getGrandTotal(), 2, '.', '');
        $params = array(
            'acquirerId'              => $acquirer_id, //
            'idCommerce'              => $idCommerce, //
            'purchaseOperationNumber' => $lastOrderId, //
            'purchaseAmount'          => $paymentAmount, //
            'purchaseCurrencyCode'    => $purchaseCurrentCode, //
            //            'commerceMallId' => '',
            'language'            => 'SP', //
            'billingFirstName'    => $firstnameBilling,
            'billingLastName'     => $lastnameBilling,
            'billingEmail'        => $emailBilling,
            'billingAddress'      => $addressBilling,
            'billingZIP'          => $zipBilling,
            'billingCity'         => $cityBilling,
            'billingState'        => $stateBilling,
            'billingCountry'      => $countryBilling,
            'shippingFirstName'   => $firstnameShipping,
            'shippingLastName'    => $lastnameShipping,
            'shippingEmail'       => $emailShipping,
            'shippingAddress'     => $addressShipping,
            'shippingZIP'         => $zipShipping,
            'shippingCity'        => $cityShipping,
            'shippingState'       => $stateShipping,
            'shippingCountry'     => $countryShipping,
            'userCommerce'        => $userCommerce, //Falta
            'userCodePayme'       => $userCodePayme, //Falta
            'descripcionProducts' => $descProductos,
            'programingLanguage'  => 'PHP',
        );
        return $params;
    }

}
