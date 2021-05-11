<?php 


class Ccc_Vendor_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->_redirect('*/account/login');
    }
}

