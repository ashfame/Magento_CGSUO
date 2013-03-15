<?php

class AnattaDesign_CustomerGroupSaveUponOrder_Model_Observer {

	public function customer_set_group($observer) {

		try {
			// Get order details
			$order = Mage::getSingleton('sales/order');
			$orderIncrementID = $observer->getEvent()->getOrder()->getIncrementId();
			$order->loadByIncrementId($orderIncrementID);
			$totalData = $order->getData();

			// Load customer object
			$customer = Mage::getModel('customer/customer')->load($totalData['customer_id']);

			// Set group id if different group was selected than what the customer belongs to, while placing the order
			if ($customer->getData('group_id') != $totalData['customer_group_id']) {
				$customer->setData('group_id', $totalData['customer_group_id']);
				$customer->save();
				Mage::log("AnattaDesign CGSUO: Customer ID {$totalData['customer_id']} is now under Customer Group ID {$totalData['customer_group_id']}");
			}

		} catch (Exception $e) {
			Mage::log("AnattaDesign CGSUO: Exception occured while changing Customer ID {$totalData['customer_id']} to Customer Group ID {$totalData['customer_group_id']}");
		}
	}

}