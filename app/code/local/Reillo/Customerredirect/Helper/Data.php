<?php

class Reillo_Customerredirect_Helper_Data extends Mage_Core_Helper_Abstract {

	/**
	 * Generate a URL redirect for session.
	 *
	 * @param  int|Mage_Core_Model_Store $store
	 * @param  string $uri
	 * @return null|string
	 */
	public function generateUrlRedirect($store, $uri)
	{
		/**@var $url Mage_Core_Model_URl */
		$store = Mage::app()->getStore($store);
		$uriPath = trim($uri, '/');
		$url = Mage::getModel('core/url');
		// TODO if uriPath is empty, use intended redirect url

		if ($store) {
			return $url->setStore($store)->addSessionParam()->getUrl($uriPath);
		}

		return null;
	}
}