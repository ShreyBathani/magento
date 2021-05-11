<?php

class Ccc_Vendor_Helper_Data extends Mage_Core_Helper_Abstract {
	
    const REFERER_QUERY_PARAM_NAME = 'referer';
    const XML_PATH_CUSTOMER_STARTUP_REDIRECT_TO_DASHBOARD = 'customer/startup/redirect_dashboard';

    public function getLoginPostUrl()
    {
        $params = array();
        if ($this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME)) {
            $params = array(
                self::REFERER_QUERY_PARAM_NAME => $this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME)
            );
        }
        return $this->_getUrl('vendor/account/loginPost', $params);
    }

    public function getAttributeInputTypes($inputType = null)
    {
        $inputTypes = array(
            'multiselect'   => array(
                'backend_model'     => 'eav/entity_attribute_backend_array'
            ),
            'boolean'       => array(
                'source_model'      => 'eav/entity_attribute_source_boolean'
            )
        );

        if (is_null($inputType)) {
            return $inputTypes;
        } else if (isset($inputTypes[$inputType])) {
            return $inputTypes[$inputType];
        }
        return array();
    }

    public function getAttributeBackendModelByInputType($inputType)
    {
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['backend_model'])) {
            return $inputTypes[$inputType]['backend_model'];
        }
        return null;
    }

    
    public function getAttributeSourceModelByInputType($inputType)
    {
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['source_model'])) {
            return $inputTypes[$inputType]['source_model'];
        }
        return null;
    }

    public function getRegisterUrl()
    {
        return $this->_getUrl('vendor/account/create');
    }

    public function getForgotPasswordUrl()
    {
        return $this->_getUrl('vendor/account/forgotpassword');
    }

    public function getRegisterPostUrl()
    {
        return $this->_getUrl('vendor/account/createpost');
    }
    
    public function getLoginUrl()
    {
        return $this->_getUrl('vendor/account/login');
    }

    public function getAccountUrl()
    {
        return $this->_getUrl('vendor/account');
    }

    public function getLogoutUrl()
    {
        return $this->_getUrl(('vendor/account/logout'));
    }

    public function getDashboardUrl()
    {
        return $this->_getUrl(('vendor/account'));
    }
}