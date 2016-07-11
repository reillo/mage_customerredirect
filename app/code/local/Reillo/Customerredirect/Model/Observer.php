<?php

class Reillo_Customerredirect_Model_Observer
{
    public function hookIntoCustomerLogin($observer) {
        // run this.
	    /**@var $customer Mage_Customer_Model_Customer*/
	    $customer = $observer->getEvent()->getCustomer();
	    $redirects = unserialize(Mage::getStoreConfig('customer/customerredirect/group_redirect'));

	    try {
		    $customer_id = $customer->getGroupId();
		    foreach ($redirects as $redirect) {
			    if ($redirect['customer_group'] == $customer_id) {
				    // do not redirect if store matched current store.
				    if ($redirect['store'] != Mage::app()->getStore()->getId()) {
					    // generate url
					    $uri = isset($redirect['uri_path']) ? $redirect['uri_path'] : '/';
					    $url = Mage::helper('reillo_customerredirect')->generateUrlRedirect($redirect['store'], $uri);

					    // redirect
					    Mage::app()->getFrontController()->getResponse()->setRedirect($url, 302);
					    Mage::app()->getResponse()->sendResponse();
					    exit;
				    }

				    // stop for first matched customer group
				    break;
			    }
		    }
	    } catch (Exception $e) {
		    Mage::logException($e);
	    }
    }
}