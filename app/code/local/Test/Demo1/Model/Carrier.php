<?php
class Test_Demo1_Model_Carrier extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'demo1';

    public function collectRates(Mage_Shipping_Model_Rate_Request $request) {
        $result = Mage::getModel('shipping/rate_result');
        $result->append($this->_getStandardShippingRate());
        return $result;
    }

    protected function _getStandardShippingRate()
    {
        $rate = Mage::getModel('shipping/rate_result_method');

        $rate->setCarrier($this->_code);
        /**
         * getConfigData(config_key) returns the configuration value for the
         * carriers/[carrier_code]/[config_key]
         */
        $rate->setCarrierTitle($this->getConfigData('title'));

        $rate->setMethod('standand');
        $rate->setMethodTitle('Standard');

        $rate->setPrice(4.99);
        $rate->setCost(0);

        return $rate;
    }

    public function getAllowedMethods()
    {
        return array(
            'standard' => 'Standard',
        );
    }
}
