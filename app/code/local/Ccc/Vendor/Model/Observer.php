<?php

class Ccc_Vendor_Model_Observer 
{
    public function addVendorIdInOrderItem($observer)
    {
        $order = $observer->getEvent()->getOrder();
        foreach ($order->getItemsCollection() as $item) {
            $vendorProduct = Mage::getModel('vendor/product')->loadByAttribute('sku', $item->getSku());
            $vendorProduct = Mage::getModel('vendor/product')->load($vendorProduct->getEntityId());
            if($vendorProduct){
                if ($vendorProduct->getVendorId()) {
                    $item->setVendorId($vendorProduct->getVendorId());
                    $item->save();
                }
            }
        }
        Mage::log($order->debug(), Zend_Log::DEBUG, 'hello.txt', true);
    }
}
