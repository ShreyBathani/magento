<?php

class Ccc_Order_Adminhtml_OrderController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('order');
        $this->_title('Orders');
        $this->_addContent($this->getLayout()->createBlock('order/adminhtml_order'));
        $this->renderLayout();        
    }

    public function gridAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('order/adminhtml_order_grid')->toHtml());
    }

    public function submitOrderAction()
    {      
        try {
            $cart = $this->getCart();
            //$customerId = $this->getRequest()->getParam('id');
            if ($cart->getTotal() == 0) {
                throw new Exception("No Items Avaiable in your Cart");
            }
            if (!$cart->getBillingAddress()->getData()) {
                throw new Exception("Please Enter Billing Address");
            }
            if (!$cart->getShippingAddress()->getData()) {
                throw new Exception("Please Enter Shipping Address");
            }
            if (!$cart->getPaymentMethodCode()) {
                throw new Exception("Please Select Payment Method");
            }
            if (!$cart->getShippingMethodCode()) {
                throw new Exception("Please Select Shipping Method");
            }
            $this->saveOrder();
            $this->deleteCart();
            $this->_getSession()->addSuccess('Order has been Successfullly saved. !!');
        }
        catch(Exception $e){
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/order_create/new', ['_current' => true]);
            return;
        }
        $this->_redirect('*/*/');
    }

    public function getCart()
    {
        $customerId = $this->getRequest()->getParam('id');
        $customer = Mage::getModel('customer/customer')->load($customerId);
        if(!$customer->getId()) {
            throw new Exception("Invalid Customer");
        }
            
        $cart = Mage::getModel('order/cart')->load($customerId, 'customer_id');
        if (!$cart->getData()) {
            throw new Exception("No Cart Found!");
        }
        return $cart;
    }

    public function saveOrder()
    {
        $cart = $this->getCart();
        $order = Mage::getModel('order/order');
        $order->setCustomerId($cart->getCustomerId())
            ->setDiscount($cart->getDiscount())
            ->setTotal($cart->getTotal())
            ->setFirstName($cart->getFirstName())
            ->setLastName($cart->getLastName())
            ->setEmail($cart->getEmail())
            ->setCustomerGroupId($cart->getCustomerGroupId())
            ->setPaymentMethodCode($cart->getPaymentMethodCode())
            ->setShippingMethodCode($cart->getShippingMethodCode())
            ->setShippingAmount($cart->getShippingAmount())
            ->setGrandTotal($cart->getTotal()+$cart->getShippingAmount())
            ->setCreatedAt(Mage::getModel('core/date')->date('Y-m-d H:i:s'))
            ->setUpdatedAt(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
        $order->save();
        $this->saveOrderItem($order);
        $this->saveOrderAddresses($order);
    }

    public function saveOrderItem($order)
    {
        $cartItems = $this->getCart()->getItems();

        foreach ($cartItems as $cartItem) {
            $orderItem = Mage::getModel('order/order_item');

            $orderItem->setOrderId($order->getId())
                ->setProductId($cartItem->getProductId())
                ->setName($cartItem->getName())
                ->setSku($cartItem->getSku())
                ->setQuantity($cartItem->getQuantity())
                ->setBasePrice($cartItem->getBasePrice())
                ->setPrice($cartItem->getPrice())
                ->setDiscount($cartItem->getDiscount())
                ->save();
        }
    }

    public function saveOrderAddresses($order)
    {
        $cart = $this->getCart();
        $cartAddresses = $cart->getAddresses();

        foreach ($cartAddresses as $cartAddress) {
            $orderAddress = Mage::getModel('order/order_address');

            $orderAddress->setOrderId($order->getId())
                ->setCustomerId($order->getCustomerId())
                ->setFirstName($cartAddress->getFirstName())
                ->setLastName($cartAddress->getLastName())
                ->setAddressType($cartAddress->getAddressType())
                ->setAddress($cartAddress->getAddress())
                ->setCity($cartAddress->getCity())
                ->setState($cartAddress->getState())
                ->setCountry($cartAddress->getCountry())
                ->setZipcode($cartAddress->getZipcode());
            $orderAddress->save();
        }
    }

    public function deleteCart()
    {
        $cart = $this->getCart();

        $cartItems = $cart->getItems();
        foreach ($cartItems as $cartItem) {
            $cartItem->delete();
        }

        $cartAddresses = $cart->getAddresses();
        foreach ($cartAddresses as $cartAddress) {
            $cartAddress->delete();
        }
        $cart->delete();
    }
}
