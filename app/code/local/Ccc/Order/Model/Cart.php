<?php
class Ccc_Order_Model_Cart extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('order/cart');
    }

    public function getBillingAddress($cart_id = null, $customerId = null, $firstname = null, $lastname = null)
    {
        $collection = Mage::getModel('order/cart_address')->getCollection();
        $collection->getSelect()
            ->where('cart_id = ?', $cart_id)
            ->Where('address_type = ?', 'billing');

        $billingAddress = $collection->getFirstItem();
        if (!$billingAddress->getData()) {
            $billingAddress = Mage::getModel('customer/customer')->load($customerId)->getDefaultBillingAddress();
            $cartBillingAddress = Mage::getModel('order/cart_address');
            $cartBillingAddress->setCartId($cart_id);
            $cartBillingAddress->setFirstName($firstname);
            $cartBillingAddress->setLastName($lastname);
            $cartBillingAddress->setAddressType('billing');
            if($billingAddress){
                $cartBillingAddress->setAddress(implode(' ', $billingAddress->getStreet()));
                $cartBillingAddress->setCity($billingAddress->getCity());
                $cartBillingAddress->setState($billingAddress->getRegion());
                $cartBillingAddress->setCountry($billingAddress->getCountryId());
                $cartBillingAddress->setZipcode($billingAddress->getPostcode());
            }   
            $cartBillingAddress->save();
            $cartBillingAddress->load($cartBillingAddress->getId());
            $billingAddress = $cartBillingAddress;
        }
        Mage::register('billing_data', $billingAddress);
        return $billingAddress;
    }

    public function getShippingAddress($cart_id = null, $customerId = null, $firstname = null, $lastname = null)
    {
        $collection = Mage::getModel('order/cart_address')->getCollection();
        $collection->getSelect()
            ->where('cart_id = ?', $cart_id)
            ->Where('address_type = ?', 'shipping');

        $shippingAddress = $collection->getFirstItem();
        if (!$shippingAddress->getData()) {
            $shippingAddress = Mage::getModel('customer/customer')->load($customerId)->getDefaultShippingAddress();
            $cartShippingAddress = Mage::getModel('order/cart_address');
            $cartShippingAddress->setCartId($cart_id);
            $cartShippingAddress->setFirstName($firstname);
            $cartShippingAddress->setLastName($lastname);
            $cartShippingAddress->setAddressType('shipping');
            if($shippingAddress){
                $cartShippingAddress->setAddress(implode(' ', $shippingAddress->getStreet()));
                $cartShippingAddress->setCity($shippingAddress->getCity());
                $cartShippingAddress->setState($shippingAddress->getRegion());
                $cartShippingAddress->setCountry($shippingAddress->getCountryId());
                $cartShippingAddress->setZipcode($shippingAddress->getPostcode());
            }
            $cartShippingAddress->save();
            $cartShippingAddress->load($cartShippingAddress->getId());
            $shippingAddress = $cartShippingAddress;
        }
        Mage::register('shipping_data', $shippingAddress);
        return $shippingAddress;
    }
}