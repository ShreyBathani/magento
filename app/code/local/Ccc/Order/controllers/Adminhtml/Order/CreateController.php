<?php

class Ccc_Order_Adminhtml_Order_CreateController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('order');
        $this->_title('Order');
        $this->renderLayout();        
    }

    public function gridAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('order/adminhtml_order_create_customer_grid')->toHtml());
    }

    public function productGridAction()
    {
        $this->getResponse()->setBody($this->getLayout()->createBlock('order/adminhtml_order_create_form_product_grid')->toHtml());
    }

    public function newAction()
    {
        try{
            $customerId = $this->getRequest()->getParam('id');
            $cart = Mage::getModel('order/cart')->load($customerId, 'customer_id');
            $customer = Mage::getModel('customer/customer')->load($customerId);
            if(!$customer->getId()) {
                throw new Exception("Invalid Customer");
            }
            if(!$cart->getData()){
                $cart->setCustomerId($customerId);
                $cart->setCustomerGroupId($customer->getGroupId());
                $cart->setFirstName($customer->getFirstname());
                $cart->setLastName($customer->getLastname());
                $cart->setEmail($customer->getEmail());
                $cart->setTotal(0.00);
                $cart->setCreatedAt(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
                $cart->setUpdatedAt(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
                $cart->save();
                $cart->load($cart->getId());
            }
            Mage::getModel('order/cart')->getBillingAddress($cart->getId(), $customerId, $cart->getFirstName(), $cart->getLastName());
            Mage::getModel('order/cart')->getShippingAddress($cart->getId(), $customerId, $cart->getFirstName(), $cart->getLastName());
            $this->updateCartPrice($cart);
            $this->updateCartTotal($cart);
            Mage::register('ccc_cart', $cart);
            $this->_getSession()->setData('ccc_cart', $cart);
            $this->loadLayout();
            $this->_setActiveMenu('order');
            $this->_title('New Order');
            $this->renderLayout();
        }
        catch(Exception $e){
            $this->_getSession()->addError($e->getMessage());
            $this->_redirect('*/*/');
        }
    }

    public function addItemsToCartAction()
    {   
        $productIds = $this->getRequest()->getParam('product');
        if (!is_array($productIds)) {
            $this->_getSession()->addError($this->__('Please select product(s).'));
        }
        else {
            if (!empty($productIds)) {
                try {
                    $total = 0;
                    $cart_id = $this->_getSession()->getData('ccc_cart')->getId();
                    foreach ($productIds as $productId) {
                        $collection = Mage::getModel('order/cart_item')->getCollection();
                        $collection->getSelect()->where('cart_id = ?', $cart_id)->where('product_id = ?', $productId);
                        $cartItem = $collection->getFirstItem();
                        if($cartItem->getData()){
                            $cartItem->setQuantity($cartItem->getQuantity() + 1);
                            $cartItem->save();
                            continue;
                        }
                        $product = Mage::getModel('catalog/product')->load($productId);
                        $cartItem->setCartId($cart_id)
                            ->setProductId($product->getId())
                            ->setName($product->getName())
                            ->setSku($product->getSku())
                            ->setQuantity(1)
                            ->setPrice($product->getPrice())
                            ->setBasePrice($product->getPrice());
                        $cartItem->save();
                    }
                    $this->updateCartPrice();
                    $this->updateCartTotal();
                    $this->_getSession()->addSuccess('Item(s) successfully added.');
                }
                catch(Exception $e){
                    $this->_getSession()->addError($e->getMessage());
                }
                //$this->getResponse()->setBody($this->getLayout()->createBlock('order/adminhtml_order_create_form')->toHtml());
            }
        }
        $this->_redirect('*/*/new', ['_current' => true]);
    }

    public function updateCartAction()
    {
        try{
            $data = $this->getRequest()->getPost('data');
            if(!$data){
                throw new Exception("No Items available in cart");
            }
            $cartItem = Mage::getModel('order/cart_item');
            foreach ($data as $key => $value) {
                $cartItem->load($key);
                if($value['quantity'] == 0){
                    $cartItem->delete();
                    continue;
                }
                $cartItem->setPrice($value['price'])->setQuantity($value['quantity']);
                $cartItem->save();
            }
            $this->updateCartPrice();
            $this->updateCartTotal();
            $this->_getSession()->addSuccess('cart successfully updated.');
        }
        catch(Exception $e){
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_redirect('*/*/new', ['_current' => true]);
    }
    
    public function updateCartPrice($cart = null)
    {
        if(!$cart){
            $cart = $this->_getSession()->getData('ccc_cart');
        }
        $cartItems = Mage::getModel('order/cart_item')->getCollection()
            ->addFieldToFilter('cart_id' , ['eq' => $cart->getId()]);
        foreach ($cartItems as $cartItem) {
            $product = Mage::getModel('catalog/product')->load($cartItem->getProductId());
            if($product->getPrice() != $cartItem->getBasePrice()){
                $cartItem->setBasePrice($product->getPrice())
                    ->setPrice($product->getPrice());
                $cartItem->save();
            }
        }
    }

    public function updateCartTotal($cart = null)
    {
        $collection = Mage::getModel('order/cart_item')->getCollection();
        if(!$cart){
            $cart = $this->_getSession()->getData('ccc_cart');
        }
        $collection->getSelect()->where('cart_id = ?', $cart->getId());
        if($collection->getData()){
            $total = 0;
            foreach ($collection as $item) {
                $total = $total + ($item->getPrice()*$item->getQuantity());
            }
            $cart->setTotal($total)->save();
        }
        else{
            $cart->setTotal(0.00)->save();
        }
    }

    public function deleteItemAction()
    {
        try {
            $cartItem = Mage::getModel('order/cart_item');
            if (!($itemId = (int) $this->getRequest()->getParam('item_id'))){
                throw new Exception('Imvalid Id');
            }
            $cartItem->load($itemId);
            if (!$cartItem->getData()) {
                throw new Exception('Item does not exist');
            }

            if (!$cartItem->delete()) {
                throw new Exception('Error in delete record', 1);
            }

            Mage::getSingleton('core/session')->addSuccess($this->__('The product has been deleted.'));
        } 
        catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('core/session')->addError($e->getMessage());
            return;
        }

        $this->_redirect('*/*/new', ['id' => $this->_getSession()->getData('ccc_cart')->getCustomerId()]);
    }

    public function saveBillingAddressAction()
    {
        try{
            $customerId = $this->getRequest()->getParam('id');
            $billingData = $this->getRequest()->getPost();
            $cartBillingAddress = Mage::getModel('order/cart')->getBillingAddress($this->_getSession()->getData('ccc_cart')->getId(), $customerId);
            $cartBillingAddress->addData($billingData);
            $cartBillingAddress->save();
            if(array_key_exists('save_in_billing_address', $billingData) && $billingData['save_in_billing_address'] == 1){
                $customerBillingAddress = Mage::getModel('customer/customer')->load($customerId)->getDefaultBillingAddress();
                if(!$customerBillingAddress){
                    //$customer = Mage::getModel('customer/customer')->load($customerId);
                    $customerBillingAddress = Mage::getModel('customer/address');
                    $customerBillingAddress->setEntityTypeId($customerBillingAddress->getEntityTypeId())
                        ->setParentId($customerId)
                        ->setCustomerId($customerId)
                        ->setFirstname($billingData['first_name'])
                        ->setLastname($billingData['last_name'])
                        ->setStreet($billingData['address'])
                        ->setRegion($billingData['state'])
                        ->setCountryId($billingData['country'])
                        ->setPostcode($billingData['zipcode'])
                        ->setIsDefaultBilling(1);
                    $customerBillingAddress->save();
                }
                else{
                    $customerBillingAddress->setFirstname($billingData['first_name'])
                        ->setLastname($billingData['last_name'])
                        ->setCity($billingData['city'])
                        ->setRegion($billingData['state'])
                        ->setPostcode($billingData['zipcode'])
                        ->setCountryId($billingData['country'])
                        ->setStreet($billingData['address']);
                    $customerBillingAddress->save();
                }
                
            }
            $this->_getSession()->addSuccess('Billing Address successfully updated.');
        }
        catch(Exception $e){
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_redirect('*/*/new', ['_current' => true]);
    }

    public function saveShippingAddressAction()
    {
        try{
            $customerId = $this->getRequest()->getParam('id');
            $shippingData = $this->getRequest()->getPost();
            $cartBillingAddress = Mage::getModel('order/cart')->getBillingAddress($this->_getSession()->getData('ccc_cart')->getId(), $customerId);
            $cartShippingAddress = Mage::getModel('order/cart')->getShippingAddress($this->_getSession()->getData('ccc_cart')->getId(), $customerId);
            if(array_key_exists('same_as_biling', $shippingData) && $shippingData['same_as_biling'] == 1){
                $data = $cartBillingAddress->getData();
                unset($data['address_id']);
                $cartShippingAddress->addData($data);
                $cartShippingAddress->setAddressType('shipping')->setSameAsBilling(1);
                $cartShippingAddress->save();
            }
            else{
                $cartShippingAddress->addData($shippingData)->setSameAsBilling(0);
                $cartShippingAddress->save();
            }
            if(array_key_exists('save_in_shipping_address', $shippingData) && $shippingData['save_in_shipping_address'] == 1){
                $customerShippingAddress = Mage::getModel('customer/customer')->load($customerId)->getDefaultShippingAddress();
                if(!$customerShippingAddress){
                    $customerShippingAddress = Mage::getModel('customer/address');
                    $customerShippingAddress->setEntityTypeId($customerShippingAddress->getEntityTypeId())
                        ->setParentId($customerId)
                        ->setCustomerId($customerId);
                        if($shippingData['same_as_biling'] == 1){
                            $customerShippingAddress->setStreet($cartBillingAddress->getAddress())
                            ->setFirstname($cartBillingAddress->getFirstName())
                            ->setLastname($cartBillingAddress->getLastName())
                            ->setRegion($cartBillingAddress->getState())
                            ->setCountryId($cartBillingAddress->getCountry())
                            ->setPostcode($cartBillingAddress->getZipcode())
                            ->setIsDefaultShipping(1);
                        }
                        else{
                            $customerShippingAddress->setStreet($shippingData['address'])
                            ->setFirstname($shippingData['first_name'])
                            ->setLastname($shippingData['last_name'])
                            ->setRegion($shippingData['state'])
                            ->setCountryId($shippingData['country'])
                            ->setPostcode($shippingData['zipcode'])
                            ->setIsDefaultShipping(1);
                        }
                    $customerShippingAddress->save();
                }
                else{
                    if($shippingData['same_as_biling'] == 1){
                        $customerShippingAddress->setStreet($cartBillingAddress->getAddress())
                            ->setRegion($cartBillingAddress->getState())
                            ->setCountryId($cartBillingAddress->getCountry())
                            ->setPostcode($cartBillingAddress->getZipcode());
                    }
                    else{
                        $customerShippingAddress->setCity($shippingData['city'])
                            ->setRegion($shippingData['state'])
                            ->setPostcode($shippingData['zipcode'])
                            ->setCountryId($shippingData['country'])
                            ->setStreet($shippingData['address']);
                        $customerShippingAddress->save();
                    }
                }
            }
            $this->_getSession()->addSuccess('Shipping Address successfully updated.');
        }
        catch(Exception $e){
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_redirect('*/*/new', ['_current' => true]);
    }

    public function saveAccountInfoAction()
    {
        try{
            $accountInfo = $this->getRequest()->getPost();
            $cart = $this->_getSession()->getData('ccc_cart');
            $cart->setFirstName($accountInfo['first_name'])->setLastName($accountInfo['last_name'])->setEmail($accountInfo['email']);
            $cart->setUpdatedAt(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
            $cart->save();
            $this->_getSession()->addSuccess('Account Information successfully updated.');
        }
        catch(Exception $e){
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_redirect('*/*/new', ['_current' => true]);
    }

    public function savePaymentMethodAction()
    {
        try {
            $paymentCode = $this->getRequest()->getPost('paymentMethod');
            $cart = $this->_getSession()->getData('ccc_cart');
            $cart->setPaymentMethodCode($paymentCode)->save(); 
            $this->_getSession()->addSuccess('Payment Method successfully updated.');
        }
        catch(Exception $e){
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_redirect('*/*/new', ['_current' => true]);
    }

    public function saveShippingMethodAction()
    {
        try {
            $shippingMethodData = $this->getRequest()->getPost('shippingMethod');
            $shippingMethodData = explode(' ', $shippingMethodData);
            $cart = $this->_getSession()->getData('ccc_cart');
            $cart->setShippingMethodCode($shippingMethodData[0])
                ->setShippingAmount($shippingMethodData[1])
                ->setUpdatedAt(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
            $cart->save(); 
            $this->_getSession()->addSuccess('Shipping Method successfully updated.');
        }
        catch(Exception $e){
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_redirect('*/*/new', ['_current' => true]);
    }

    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }
}
