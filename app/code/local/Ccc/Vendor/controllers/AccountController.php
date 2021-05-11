<?php

class Ccc_Vendor_AccountController extends Mage_Core_Controller_Front_Action 
{
    public function indexAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/login');
            return;
        }
        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');

        $this->getLayout()->getBlock('content')->append(
            $this->getLayout()->createBlock('vendor/account_dashboard')
        );
        $this->getLayout()->getBlock('head')->setTitle($this->__('My Account'));
        $this->renderLayout();
    }

    public function loginAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $this->getResponse()->setHeader('Login-Required', 'true');
        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');
        $this->renderLayout();
    }

    public function loginPostAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_redirect('*/*/');
            return;
        }

        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        $session = $this->_getSession();

        if ($this->getRequest()->isPost()) {
            $login = $this->getRequest()->getPost('login');
            if (!empty($login['email']) && !empty($login['password'])) {
                try {
                    $session->login($login['email'], $login['password']);

                    $this->_welcomeVendor($session->getVendor(), true);
                } catch (Mage_Core_Exception $e) {
                    echo $e->getMessage();
                    switch ($e->getCode()) {
                        case Ccc_Vendor_Model_Vendor::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
                            $message = $e->getMessage();
                            break;
                        default:
                            $message = $e->getMessage();
                    }
                    $session->addError($message);
                    $session->setEmail($login['email']);
                } catch (Exception $e) {
                    echo $e->getMessage();
                    Mage::logException($e); // PA DSS violation: this exception log can disclose vendor password
                }
            } else {
                $session->addError($this->__('Login and password are required.'));
            }
        }

        $this->_loginPostRedirect();
    }

    public function _loginPostRedirect()
    {
        $session = $this->_getSession();

        if (!$session->getBeforeAuthUrl() || $session->getBeforeAuthUrl() == Mage::getBaseUrl()) {
            // Set default URL to redirect vendor to
            $session->setBeforeAuthUrl($this->_getHelper('vendor')->getAccountUrl());
            // Redirect vendor to the last page visited after logging in
            if ($session->isLoggedIn()) {
                if (!Mage::getStoreConfigFlag(
                    Ccc_Vendor_Helper_Data::XML_PATH_CUSTOMER_STARTUP_REDIRECT_TO_DASHBOARD
                )) {
                    $referer = $this->getRequest()->getParam(Ccc_Vendor_Helper_Data::REFERER_QUERY_PARAM_NAME);
                    if ($referer) {
                        // Rebuild referer URL to handle the case when SID was changed
                        $referer = $this->_getModel('core/url')
                            ->getRebuiltUrl( $this->_getHelper('core')->urlDecodeAndEscape($referer));
                        if ($this->_isUrlInternal($referer)) {
                            $session->setBeforeAuthUrl($referer);
                        }
                    }
                } else if ($session->getAfterAuthUrl()) {
                    $session->setBeforeAuthUrl($session->getAfterAuthUrl(true));
                }
            } else {
                $session->setBeforeAuthUrl( $this->_getHelper('vendor')->getLoginUrl());
            }
        } else if ($session->getBeforeAuthUrl() ==  $this->_getHelper('vendor')->getLogoutUrl()) {
            $session->setBeforeAuthUrl( $this->_getHelper('vendor')->getDashboardUrl());
        } else {
            if (!$session->getAfterAuthUrl()) {
                $session->setAfterAuthUrl($session->getBeforeAuthUrl());
            }
            if ($session->isLoggedIn()) {
                $session->setBeforeAuthUrl($session->getAfterAuthUrl(true));
            }
        }
        $this->_redirectUrl($session->getBeforeAuthUrl(true));
    }

    public function logoutAction()
    {
        $session = $this->_getSession();
        $session->logout()->renewSession();
        
        $this->_redirect('*/account/login');
    }
    
    public function createAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*');
            return;
        }

        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');
        $this->renderLayout();
    }

    public function createpostAction()
    {
        $errUrl = $this->_getUrl('*/*/create', array('_secure' => true));
        if (!$this->_validateFormKey()) {
            $this->_redirectError($errUrl);
            return;
        }
        $session = $this->_getSession();
        
        if ($session->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        if (!$this->getRequest()->isPost()) {
            $this->_redirectError($errUrl);
            return;
        }
        
        $vendor = $this->_getVendor();
        
        try {
            $errors = $this->_getVendorErrors($vendor);
            
            if (empty($errors)) {
                $vendor->cleanPasswordsValidationData();
                $vendor->save();
                $this->_dispatchRegisterSuccess($vendor);
                $this->_successProcessRegistration($vendor);
                return;
            }
            else{
                $this->_addSessionError($errors);
            }

        } catch (Mage_Core_Exception $e) {
            $session->setVendorFormData($this->getRequest()->getPost());
            if ($e->getCode() === Ccc_Vendor_Model_Vendor::EXCEPTION_EMAIL_EXISTS) {
                $url = $this->_getUrl('vendor/account/forgotpassword');
                $message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);
            } else {
                $message = $this->_escapeHtml($e->getMessage());
            }
            $session->addError($message);
        } catch (Exception $e) {
            $session->setVendorFormData($this->getRequest()->getPost());
            $session->addException($e, $this->__('Cannot save the vendor.'));
        }

        $this->_redirectError($errUrl);
    }

    public function editAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');

        $block = $this->getLayout()->getBlock('vendor_edit');
        if ($block) {
            $block->setRefererUrl($this->_getRefererUrl());
        }
        //$data = $this->_getSession()->getVendorFormData(true);
        $vendor = $this->_getSession()->getVendor();
        
        /* if (!empty($data)) {
            $vendor->addData($data);
        } */
        if ($this->getRequest()->getParam('changepass') == 1) {
            $vendor->setChangePassword(1);
        }

        $this->getLayout()->getBlock('head')->setTitle($this->__('Account Information'));
        $this->getLayout()->getBlock('messages')->setEscapeMessageFlag(true);
        $this->renderLayout();
    }


    public function editPostAction()
    {
        try {
            if (!$this->_validateFormKey()) {
                $this->_redirect('vendor/*/edit');
            }

            if ($this->getRequest()->isPost()) {
                $vendor = $this->_getSession()->getVendor();
                $vendor->setOldEmail($vendor->getEmail());

                $vendorForm = Mage::getModel('vendor/form');
                $vendorForm->setFormCode('vendor_account_edit')
                    ->setEntity($vendor);

                $vendorData = $vendorForm->extractData($this->getRequest());
              
                $errors = array();
                $vendorErrors = $vendorForm->validateData($vendorData);
                
          
                if ($vendorErrors !== true) {
                    $errors = array_merge($vendorErrors, $errors);
                } else {
                    $vendorForm->compactData($vendorData);
                    $errors = array();
                    

                    if (!$vendor->validatePassword($this->getRequest()->getPost('current_password'))) {
                        $errors[] = $this->__('Invalid Current Password');
                    }

                    $isChangeEmail = ($vendor->getOldEmail() != $vendor->getEmail() ? true : false);
                    $vendor->setIsChangeEmail($isChangeEmail);
                    
                    $vendor->setIsChangePassword($this->getRequest()->getPost('change_password'));
                

                    if ($vendor->getIsChangePassword()) {
                        
                        $newPassword    = $this->getRequest()->getPost('password');
                        $confirmationPassword   = $this->getRequest()->getPost('confirmation');

                        if (strlen($newPassword)) {
                            $vendor->setPassword($newPassword);
                            $vendor->setPasswordConfirmation($confirmationPassword);
                        } else {
                            $errors[] = $this->__('New Password cannot be empty.');
                        }
                    }

                    $vendorErrors = $vendor->validate();
                    if (is_array($vendorErrors)) {
                        array_merge($errors, $vendorErrors);
                    }
                }

                if (!empty($errors)) {
                    $this->_getSession()->setVendorFormData($this->getRequest()->getPost());
                    foreach ($errors as $error) {
                        $this->_getSession()->addError($error);
                    }
                    $this->_redirect('*/*/edit');
                    return $this;
                }
            }

            $vendor->cleanPasswordsValidationData();
            if ($vendor->getIsChangeEmail()) {
                // make attribute in databse
                // $vendor->setRpToken(null);
                // $vendor->setRpTokenCreatedAt(null);
            }
            $vendor->save();
            $this->_getSession()->setVendor($vendor)
            ->addSuccess($this->__('The account information has been saved.'));
            $this->_redirect('vendor/account');
            return;
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->setVendorFormData($this->getRequest()->getPost())
                ->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->setVendorFormData($this->getRequest()->getPost())
                ->addException($e, $this->__($e->getMessage()));
        }
        $this->_redirect('*/*/edit');
    }

    protected function _getVendor()
    {
        $vendor = $this->_getFromRegistry('current_vendor');
        if (!$vendor) {
            $vendor = $this->_getModel('vendor/vendor')->setId(null);
        }
        return $vendor;
    }

    protected function _dispatchRegisterSuccess($vendor)
    {
        Mage::dispatchEvent('vendor_register_success',
            array('account_controller' => $this, 'vendor' => $vendor)
        );
    }

    protected function _successProcessRegistration(Ccc_Vendor_Model_Vendor $vendor)
    {
        $session = $this->_getSession();
        $session->setVendorAsLoggedIn($vendor);
        $url = $this->_welcomeVendor($vendor);

        $this->_redirectSuccess($url);
        return $this;
    }

    protected function _welcomeVendor(Ccc_Vendor_Model_Vendor $vendor, $isJustConfirmed = false)
    {
        $this->_getSession()->addSuccess(
            $this->__('Thank you for registering with %s.', Mage::app()->getStore()->getFrontendName())
        );
        $successUrl = $this->_getUrl('*/*/index', array('_secure' => true));
        if ($this->_getSession()->getBeforeAuthUrl()) {
            $successUrl = $this->_getSession()->getBeforeAuthUrl(true);
        }
        return $successUrl;
    }

    protected function _addSessionError($errors)
    {
        $session = $this->_getSession();
        $session->setVendorFormData($this->getRequest()->getPost());
        if (is_array($errors)) {
            foreach ($errors as $errorMessage) {
                $session->addError($this->_escapeHtml($errorMessage));
            }
        } else {
            $session->addError($this->__('Invalid vendor data'));
        }
    }

    protected function _escapeHtml($text)
    {
        return Mage::helper('core')->escapeHtml($text);
    }

    protected function _getApp()
    {
        return Mage::app();
    }

    protected function _getHelper($path)
    {
        return Mage::helper($path);
    }
    
    protected function _getSession()
    {
        return Mage::getSingleton('vendor/session');
    }

    protected function _getUrl($url, $params = array())
    {
        return Mage::getUrl($url, $params);
    }

    protected function _getFromRegistry($path)
    {
        return Mage::registry($path);
    }

    public function _getModel($path, $arguments = array())
    {
        return Mage::getModel($path, $arguments);
    }

    protected function _getVendorErrors($vendor)
    {
        $errors = array();
        $request = $this->getRequest();

        $vendorForm = $this->_getVendorForm($vendor);
        $vendorData = $vendorForm->extractData($request);
        
        $vendorErrors = $vendorForm->validateData($vendorData);
        
        if ($vendorErrors !== true) {
            $errors = array_merge($vendorErrors, $errors);
        } else {
            $vendorForm->compactData($vendorData);
            $vendor->setPassword($request->getPost('password'));
            $vendor->setPasswordConfirmation($request->getPost('confirmation'));
            $vendorErrors = $vendor->validate();
            if (is_array($vendorErrors)) {
                $errors = array_merge($vendorErrors, $errors);
            }
        }
        
        return $errors;
    }

    protected function _getVendorForm($vendor)
    {
        /* @var $vendorForm Mage_Vendor_Model_Form */
        $vendorForm = $this->_getModel('vendor/form');
        $vendorForm->setFormCode('vendor_account_create');
        $vendorForm->setEntity($vendor);
        return $vendorForm;
    }
}
