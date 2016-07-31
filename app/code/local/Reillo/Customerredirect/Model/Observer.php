<?php

class Reillo_Customerredirect_Model_Observer
{
	public function hookIntoCustomerLogin($observer) {
		// run this.
		/**@var $customer Mage_Customer_Model_Customer*/
		$customer = $observer->getEvent()->getCustomer();
		$redirects = unserialize(Mage::getStoreConfig('customer/customerredirect/group_redirect'));
		$session = Mage::getSingleton('customer/session');

		// sid should be enabled
		if (!$session->useSid()) {

			// log if redirects is set and
			if (!empty($redirects)) {
				Mage::log('Customer redirect module requires `Use SID on Frontend` from `System | Configuration | Web` to be enabled.', Zend_Log::INFO);
			}

			return ;
		}

		try {
			$group_id = $customer->getGroupId();

			if (!empty($redirects)) {
				foreach ($redirects as $redirect) {
					if ($redirect['customer_group'] == $group_id) {
						// do not redirect if store matched current store.
						if ($redirect['store'] != Mage::app()->getStore()->getId()) {
							// generate url
							$uri = isset($redirect['uri_path']) ? $redirect['uri_path'] : '/';
							$url = Mage::helper('reillo_customerredirect')->generateUrlRedirect($redirect['store'], $uri);

							// redirect
							$session->setBeforeAuthUrl($url)
								->setAfterAuthUrl($url);
						}

						// stop for first matched customer group
						break;
					}
				}
			}
		} catch (Exception $e) {
			Mage::logException($e);
		}
	}
}