<?php

class Ccc_Order_Block_Adminhtml_Order_Create_Form_BillingAddress_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected $cart = null;
    protected $billingAddress = null;

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

    public function setBillingAddress($billingAddress = null)
    {
        $address = $this->getCart()->getBillingAddress();
        
        if ($address->getData()) {
            $this->billingAddress = $address;
            return $this;
        }
        
        $billingAddress = $this->getCart()->getCustomer()->getDefaultBillingAddress();
        if ($billingAddress) {
            $cartBillingAddress = $address;
            $cartBillingAddress->setCartId($this->getCart()->getId());
            $cartBillingAddress->setFirstName($billingAddress->getFirstname());
            $cartBillingAddress->setLastName($billingAddress->getLastname());
            $cartBillingAddress->setAddressType(Ccc_Order_Model_Cart_Address::ADDRESS_TYPE_BILLING);
            $cartBillingAddress->setAddress(implode(' ', $billingAddress->getStreet()));
            $cartBillingAddress->setCity($billingAddress->getCity());
            $cartBillingAddress->setState($billingAddress->getRegion());
            $cartBillingAddress->setCountry($billingAddress->getCountryId());
            $cartBillingAddress->setZipcode($billingAddress->getPostcode());
            $cartBillingAddress->save();
            $this->billingAddress = $cartBillingAddress;
            return $this;
        }
        $this->billingAddress = $address;
        return $this;
    }
    
    public function getBillingAddress()
    {
        if (!$this->billingAddress) {
            $this->setBillingAddress();
        }
        return $this->billingAddress;
    }

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id' => 'billingaddress_form',
                'action' => $this->getUrl('*/*/saveBillingAddress', array('id' => $this->getRequest()->getParam('id'))),
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

        $form->addField('saveInBillingAddress', 'checkbox', array(
            'label'     => Mage::helper('order')->__('Save in Address Book'),
            'name'      => 'saveInBillingAddress',
            'checked' => false,
            'onclick' => 'this.value = this.checked ? 1 : 0;',
            'onchange' => "",
            'disabled' => false,
            'tabindex' => 1
        ));

        if (Mage::registry('billing_data'))
        {
            $form->setValues(Mage::registry('billing_data')->getData());
        }
        return parent::_prepareForm();
    }

    public function getCountryOptions()
    {
        return Mage::getModel('adminhtml/system_config_source_country')->toOptionArray();
    }
}