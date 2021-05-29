<?php

class Ccc_Vendor_Model_Observer 
{
    public function beforeLoadLayout($observer)
    {
        $loggedIn = Mage::getSingleton('vendor/session')->isLoggedIn();

        $observer->getEvent()->getLayout()->getUpdate()
            ->addHandle('vendor_logged_' . ($loggedIn ? 'in' : 'out'));
    }
    
    public function addVendorIdInOrderItem($observer)
    {
        $order = $observer->getEvent()->getOrder();
        foreach ($order->getItemsCollection() as $item) {
            $vendorProduct = Mage::getModel('vendor/product')->loadByAttribute('sku', $item->getSku());
            if($vendorProduct){
                $vendorProduct = Mage::getModel('vendor/product')->load($vendorProduct->getEntityId());
                if ($vendorProduct->getVendorId()) {
                    $item->setVendorId($vendorProduct->getVendorId());
                    $item->save();
                }
            }
        }
    }
}
