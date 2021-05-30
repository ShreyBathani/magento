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

    public function getCart()
	{
		$customerId = $this->getRequest()->getParam('id');
        $customer = Mage::getModel('customer/customer')->load($customerId);
        if(!$customer->getId()) {
            throw new Exception("Invalid Customer");
        }
            
        $cart = Mage::getModel('order/cart')->load($customerId, 'customer_id');
		
        if($cart->getData()){
			return $cart;
		}
        
		$cart = Mage::getModel('order/cart');
		$cart->setCustomerId($customerId);
        $cart->setCustomerGroupId($customer->getGroupId());
        $cart->setFirstName($customer->getFirstname());
        $cart->setLastName($customer->getLastname());
        $cart->setEmail($customer->getEmail());
        $cart->setTotal(0.00);
        $cart->setCreatedAt(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
        $cart->setUpdatedAt(Mage::getModel('core/date')->date('Y-m-d H:i:s'));
        $cart->save();
		return $cart;
	}

    public function newAction()
    {
        try{
            $cart = $this->getCart();
            $this->updateCartItemPrice();
            $this->updateCartTotal($cart);
            $this->loadLayout();
            $this->getLayout()->getBlock('main')->setCart($cart);
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
        try {
            $productIds = $this->getRequest()->getParam('product');
            if (!is_array($productIds)) {
                $this->_getSession()->addError($this->__('Please select product(s).'));
            }
            else {
                if (!empty($productIds)) {
                
                    $cart = $this->getCart();
                    foreach ($productIds as $productId) {
                        $product = Mage::getModel('catalog/product')->load($productId);
                        $cart->addItemToCart($product);
                    }
                    $this->updateCartTotal($cart);
                    $this->_getSession()->addSuccess('Item(s) successfully added.');
                }
            }
        }
        catch(Exception $e){
            $this->_getSession()->addError($e->getMessage());
        }
        //$this->getResponse()->clearHeaders()->setHeader('Content-type','application/json',true);
        //$this->getResponse()->setBody($this->getLayout()->createBlock('main')->toHtml());
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
            foreach ($data as $productId => $value) {
                $cartItem->load($productId);
                if($value['quantity'] == 0){
                    $cartItem->delete();
                    continue;
                }
                $cartItem->setPrice($value['price'])->setQuantity($value['quantity']);
                $cartItem->save();
            }
            $this->updateCartItemPrice();
            $this->updateCartTotal();
            $this->_getSession()->addSuccess('cart successfully updated.');
        }
        catch(Exception $e){
            $this->_getSession()->addError($e->getMessage());
        }
        $this->_redirect('*/*/new', ['_current' => true]);
    }
    
    public function updateCartItemPrice()
    {
        $cartItems = $this->getCart()->getItems();
        if($cartItems->getData()){
            foreach ($cartItems as $cartItem) {
            $product = Mage::getModel('catalog/product')->load($cartItem->getProductId());
            if($product->getPrice() != $cartItem->getBasePrice()){
                $cartItem->setBasePrice($product->getPrice())
                    ->setPrice($product->getPrice());
                $cartItem->save();
                }
            }
        }
    }

    public function updateCartTotal($cart = null)
    {
        if(!$cart){
            $cart = $this->getCart();
        }
        $items = $cart->getItems();
        if($items->getData()){
            $total = 0;
            foreach ($items as $item) {
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
                throw new Exception('Invalid Id');
            }
            $cartItem->load($itemId);
            if (!$cartItem->getData()) {
                throw new Exception('Item does not exist');
            }

            if (!$cartItem->delete()) {
                throw new Exception('Error in delete record', 1);
            }
            $this->_getSession()->addSuccess('The product has been deleted.');
        } 
        catch (Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }

        $this->_redirect('*/*/new', ['id' => $this->getRequest()->getParam('id')]);
    }

    public function saveBillingAddressAction()
    {
        try{
            $cart = $this->getCart();
            $customer = $cart->getCustomer();
            $billingData = $this->getRequest()->getPost();
            $cartBillingAddress = $cart->getBillingAddress();
            $cartBillingAddress->addData($billingData)->setCartId($cart->getId())->setAddressType(Ccc_Order_Model_Cart_Address::ADDRESS_TYPE_BILLING);
            $cartBillingAddress->save();
            if(array_key_exists('save_in_billing_address', $billingData) && $billingData['save_in_billing_address'] == 1){
                $customerBillingAddress = $customer->getDefaultBillingAddress();
                if(!$customerBillingAddress){
                    $customerBillingAddress = Mage::getModel('customer/address');
                    $customerBillingAddress->setParentId($customer->getId())
                        ->setCustomerId($customer->getId())
                        ->setFirstname($billingData['first_name'])
                        ->setLastname($billingData['last_name'])
                        ->setStreet($billingData['address'])
                        ->setRegion($billingData['state'])
                        ->setCity($billingData['city'])
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
            $cart = $this->getCart();
            $customer = $cart->getCustomer();
            $shippingData = $this->getRequest()->getPost();
            $cartBillingAddress = $cart->getBillingAddress();
            $cartShippingAddress = $cart->getShippingAddress();
            if(array_key_exists('same_as_biling', $shippingData) && $shippingData['same_as_biling'] == 1){
                if(!$cartBillingAddress->getData()){
                    throw new Exception("Please fill billing address first ..!");
                }
                $data = $cartBillingAddress->getData();
                unset($data['address_id']);
                $cartShippingAddress->addData($data)
                    ->setCartId($cart->getId())
                    ->setAddressType(Ccc_Order_Model_Cart_Address::ADDRESS_TYPE_SHIPPING)
                    ->setSameAsBilling(1);
                $cartShippingAddress->save();
            }
            else{
                $cartShippingAddress->addData($shippingData)
                    ->setCartId($cart->getId())
                    ->setAddressType(Ccc_Order_Model_Cart_Address::ADDRESS_TYPE_SHIPPING)
                    ->setSameAsBilling(0);
                $cartShippingAddress->save();
            }
            if(array_key_exists('save_in_shipping_address', $shippingData) && $shippingData['save_in_shipping_address'] == 1){
                $customerShippingAddress = $customer->getDefaultShippingAddress();
                if(!$customerShippingAddress){
                    $customerShippingAddress = Mage::getModel('customer/address');
                    $customerShippingAddress->setParentId($customer->getId())
                        ->setCustomerId($customer->getId());
                        if($shippingData['same_as_biling'] == 1){
                            $customerShippingAddress->setStreet($cartBillingAddress->getAddress())
                            ->setFirstname($cartBillingAddress->getFirstName())
                            ->setLastname($cartBillingAddress->getLastName())
                            ->setCity($cartBillingAddress->getCity())
                            ->setRegion($cartBillingAddress->getState())
                            ->setCountryId($cartBillingAddress->getCountry())
                            ->setPostcode($cartBillingAddress->getZipcode())
                            ->setIsDefaultShipping(1);
                        }
                        else{
                            $customerShippingAddress->setStreet($shippingData['address'])
                            ->setFirstname($shippingData['first_name'])
                            ->setLastname($shippingData['last_name'])
                            ->setCity($cartBillingAddress['city'])
                            ->setRegion($cartBillingAddress['state'])
                            ->setCountryId($shippingData['country'])
                            ->setPostcode($shippingData['zipcode'])
                            ->setIsDefaultShipping(1);
                        }
                    $customerShippingAddress->save();
                }
                else{
                    if($shippingData['same_as_biling'] == 1){
                        $customerShippingAddress->setStreet($cartBillingAddress->getAddress())
                            ->setFirstname($cartBillingAddress->getFirstName())
                            ->setLastname($cartBillingAddress->getLastName())
                            ->setCity($cartBillingAddress->getCity())
                            ->setRegion($cartBillingAddress->getState())
                            ->setCountryId($cartBillingAddress->getCountry())
                            ->setPostcode($cartBillingAddress->getZipcode());
                    }
                    else{
                        $customerShippingAddress->setCity($shippingData['city'])
                            ->setFirstname($shippingData['first_name'])
                            ->setLastname($shippingData['last_name'])
                            ->setRegion($shippingData['state'])
                            ->setPostcode($shippingData['zipcode'])
                            ->setCountryId($shippingData['country'])
                            ->setStreet($shippingData['address']);
                    }
                    $customerShippingAddress->save();
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
            $cart = $this->getCart();
            $cart->setPaymentMethodCode($paymentCode)
                ->setUpdatedAt(Mage::getModel('core/date')->date('Y-m-d H:i:s'))
                ->save(); 
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
            $cart = $this->getCart();
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
