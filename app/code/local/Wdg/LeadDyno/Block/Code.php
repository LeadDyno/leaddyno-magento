<?php

class Wdg_LeadDyno_Block_Code extends Mage_Core_Block_Template {
	
	public function getHost(){
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
	}
	
	public function isActive(){
		return Mage::getStoreConfigFlag(Wdg_LeadDyno_Helper_Data::XML_PATH_ACTIVE);
	}
	
	public function getPublicKey(){
		return Mage::getStoreConfig(Wdg_LeadDyno_Helper_Data::XML_PATH_PUBLIC_KEY);
	}
	
}