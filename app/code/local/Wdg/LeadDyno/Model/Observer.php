<?php

class Wdg_LeadDyno_Model_Observer {
	
	public function salesOrderPaymentPlaceEnd(Varien_Event_Observer $observer){
		
		if (Mage::getStoreConfigFlag(Wdg_LeadDyno_Helper_Data::XML_PATH_ACTIVE)){
			
			$privateKey = Mage::getStoreConfig(Wdg_LeadDyno_Helper_Data::XML_PATH_PRIVATE_KEY);
			if ($privateKey){
				
				$client = new Zend_Http_Client();
				$order = $observer->getEvent()->getPayment()->getOrder();
				
				$client->setUri('https://api.leaddyno.com/v1/purchases');
				$client->setMethod(Zend_Http_Client::POST);
				
				$client->setParameterPost(array(
					'key' => $privateKey,
					'email' => $order->getCustomerEmail(),
					'purchase_code' => $order->getIncrementId(),
					'purchase_amount' => $order->getTotalDue()
				));
				
				@$client->request();
				
			}
			
		}
		
	}
	
	public function salesOrderPaymentRefund(Varien_Event_Observer $observer){
		
		if (Mage::getStoreConfigFlag(Wdg_LeadDyno_Helper_Data::XML_PATH_ACTIVE)){
			
			$privateKey = Mage::getStoreConfig(Wdg_LeadDyno_Helper_Data::XML_PATH_PRIVATE_KEY);
			if ($privateKey){
				
				$client = new Zend_Http_Client();
				$order = $observer->getEvent()->getPayment()->getOrder();
				
				$client->setUri('https://api.leaddyno.com/v1/purchases/by_purchase_code');
				$client->setMethod(Zend_Http_Client::GET);
				
				$client->setParameterGet(array(
					'key' => $privateKey,
					'purchase_code' => $order->getIncrementId()
				));
				
				$purchase = @$client->request();
				
				if ($purchase->getStatus() == 200){
					$purchase = json_decode($purchase->getRawBody());
					if (is_object($purchase) && isset($purchase->id) && $purchase->id){
						
						$curlSession = curl_init();
						
						curl_setopt($curlSession, CURLOPT_URL, 'https://api.leaddyno.com/v1/purchases/'.$purchase->id);
						curl_setopt($curlSession, CURLOPT_CUSTOMREQUEST, 'DELETE');
						curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, 1);
						
						curl_setopt($curlSession, CURLOPT_POSTFIELDS, http_build_query(array(
							'key' => $privateKey,
							'source' => 'api',
							'cancellation_code' => $order->getIncrementId()
						)));
						
						curl_exec($curlSession);
						curl_close($curlSession);
						
					}
				}
				
			}
			
		}
		
	}
	
}