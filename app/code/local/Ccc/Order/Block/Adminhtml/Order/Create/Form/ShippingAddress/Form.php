<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_ShippingAddress_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected $cart = null;
    protected $shippingAddress = null;

    public function setCart(Ccc_Order_Model_Cart $cart)
    {
        $this->cart = $cart;
        return $this;
    }

    public function getCart()
    {
        if (!$this->cart) {
            Mage::throwException(Mage::helper('order')->__('Cart Is not set.'));
        }
        return $this->cart;
    }

    public function setShippingAddress($shippingAddress = null)
    {
        $address = $this->getCart()->getShippingAddress();
        if ($address->getData()) {
            $this->shippingAddress = $address;
            return $this;
        }
        
        $shippingAddress = $this->getCart()->getCustomer()->getDefaultShippingAddress();
        if ($shippingAddress) {
            $cartShippingAddress = $address;
            $cartShippingAddress->setCartId($this->getCart()->getId());
            $cartShippingAddress->setFirstName($shippingAddress->getFirstname());
            $cartShippingAddress->setLastName($shippingAddress->getLastname());
            $cartShippingAddress->setAddressType(Ccc_Order_Model_Cart_Address::ADDRESS_TYPE_SHIPPING);
            $cartShippingAddress->setAddress(implode(' ', $shippingAddress->getStreet()));
            $cartShippingAddress->setCity($shippingAddress->getCity());
            $cartShippingAddress->setState($shippingAddress->getRegion());
            $cartShippingAddress->setCountry($shippingAddress->getCountryId());
            $cartShippingAddress->setZipcode($shippingAddress->getPostcode());
            $cartShippingAddress->save();
            $this->shippingAddress = $cartShippingAddress;
            return $this;
        }
        $this->shippingAddress = $address;
        return $this;
    }

    public function getShippingAddress()
    {
        if (!$this->shippingAddress) {
            $this->setShippingAddress();
        }
        return $this->shippingAddress;
    }
    
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id' => 'shippingaddress_form',
                'action' => $this->getUrl('*/*/saveShippingAddress', array('id' => $this->getRequest()->getParam('id'))),
                'method' => 'post',
            )
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        $form->addField('customer_name', 'text', array(
        'label' => Mage::helper('order')->__('Name'),
        'class' => 'required-entry',
        'required' => true,
        'name' => 'customer_name',
        ));
            
        $form->addField('address', 'text', array(
        'label' => Mage::helper('order')->__('Address'),
        'class' => 'required-entry',
        'required' => true,
        'name' => 'address',
        ));
            
        $form->addField('city', 'text', array(
        'label' => Mage::helper('order')->__('City'),
        'class' => 'required-entry',
        'required' => true,
        'name' => 'city',
        ));
    
        $form->addField('state', 'text', array(
        'label' => Mage::helper('order')->__('State'),
        'class' => 'required-entry',
        'required' => true,
        'name' => 'state',
        ));

        $form->addField('zipcode', 'text', array(
        'label' => Mage::helper('order')->__('Zipcode'),
        'class' => 'required-entry',
        'required' => true,
        'name' => 'zipcode',
        ));

        $form->addField('country', 'select', array(
        'label' => Mage::helper('order')->__('Country'),
        'class' => 'required-entry',
        'required' => true,
        'name' => 'country',
        'values' => Mage::getModel('adminhtml/system_config_source_country')->toOptionArray(),
        ));

        $form->addField('sameAsBilling', 'checkbox', array(
            'label'     => Mage::helper('order')->__('Same as Billing'),
            'name'      => 'same_as_billing',
            'value'  => 'same_as_billing',
            'onclick' => "",
        ));
        
        $form->addField('saveInShippingingAddress', 'checkbox', array(
            'label'     => Mage::helper('order')->__('Save in Address Book'),
            'name'      => 'saveInShippingingAddress',
            'checked' => false,
            'onclick' => "",
            'onchange' => "",
            'value'  => "1",
            'disabled' => false,
            'tabindex' => 1
        ));


        if ( Mage::registry('shipping_data') )
        {
            $form->setValues(Mage::registry('shipping_data')->getData());
        }
        return parent::_prepareForm();
    }

    public function getCountryOptions()
    {
        return Mage::getModel('adminhtml/system_config_source_country')->toOptionArray();
    }
}