<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0).
 * It is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */
require_once 'Mage/Adminhtml/controllers/Sales/OrderController.php';

class Kwetal_DeleteOrders_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{
	protected $_db;
	
    public function deleteorderAction()
    { 
		$resource = Mage::getSingleton('core/resource');
		$orderTables = array();
		
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_creditmemo_item'),
							   'where' => "parent_id IN
										     (SELECT entity_id FROM " . $resource->getTableName('sales_flat_invoice') . "
											  WHERE order_id = ?)",
							   'key' => 'orderId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_creditmemo_comment'),
							   'where' => "order_id= ?",
							   'key' => 'orderId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_creditmemo'),
								'where' => "WHERE order_id = ?",
								'key' => 'orderId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_creditmemo_grid'),
							   'where' =>  "WHERE order_id = ?",
							   'key' => 'orderId');
		
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_invoice_item'),
							   'where' => "parent_id IN
										     (SELECT entity_id FROM " . $resource->getTableName('sales_flat_invoice') . "
											  WHERE order_id = ?)",
							   'key' => 'orderId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_invoice_comment'),
							   'where' => "parent_id IN
										   (SELECT entity_id FROM " . $resource->getTableName('sales_flat_invoice') . "
										    WHERE order_id = ?)",
							   'key' => 'orderId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_invoice'),
							   'where' => "order_id = ?",
							   'key' => 'orderId');		
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_invoice_grid'),
							   'where' => "order_id = ?",
							   'key' => 'orderId');
		
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_quote_address_item'),
							   'where' => "WHERE parent_item_id IN
										    (SELECT address_id FROM " . $resource->getTableName('sales_flat_quote_address') . "
											 WHERE quote_id = ?)",
							   'key' => 'quoteId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_quote_shipping_rate'),
							   'where' => "address_id IN
							                (SELECT address_id FROM " . $resource->getTableName('sales_flat_quote_address') . "
											 WHERE quote_id = ?)",
							   'key' => 'quoteId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_quote_item_option'),
							   'where' => "item_id IN
											(SELECT item_id FROM " . $resource->getTableName('sales_flat_quote_item') . "
											 WHERE quote_id = ?)",
							   'key' => 'quoteId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_quote'),
							   'where' => "entity_id = ?",
							   'key' => 'quoteId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_quote_address'),
							   'where' => "quote_id = ?",
							   'key' => 'quoteId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_quote_item'),
							   'where' => "quote_id = ?",
							   'key' => 'quoteId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_quote_payment'),
							   'where' => "quote_id = ?",
							   'key' => 'quoteId');
		
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_shipment_comment'),
							   'where'=> "parent_id IN
										    (SELECT entity_id FROM " . $resource->getTableName('sales_flat_shipment') . "
											 WHERE order_id = ?)",
							   'key' => 'orderId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_shipment_item'),
							   'where'=> "parent_id IN
										    (SELECT entity_id FROM " . $resource->getTableName('sales_flat_shipment') . "
											 WHERE order_id = ?)",
							   'key' => 'orderId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_shipment_track'),
							   'where'=> "order_id  IN
										    (SELECT entity_id FROM " . $resource->getTableName('sales_flat_shipment') . "
											 WHERE order_id = ?)",
							   'key' => 'orderId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_shipment'),
							   'where'=> "order_id = ?",
							   'key' => 'orderId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_shipment_grid'),
							   'where'=> "order_id = ?",
							   'key' => 'orderId');		
		
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_order'),
							   'where'=> "entity_id = ?",
							   'key' => 'orderId');	
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_order_address')    ,
							   'where'=> "parent_id = ?",
							   'key' => 'orderId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_order_item'),
							   'where'=> "order_id = ?",
							   'key' => 'orderId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_order_payment'),
							   'where'=> "parent_id = ?",
							   'key' => 'orderId');
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_order_status_history'),
							   'where'=> "parent_id = ?",
							   'key' => 'orderId');					
		$orderTables[] = array('table' => $resource->getTableName('sales_flat_order_grid'),
							   'where'=> "increment_id = ?",
							   'key' => 'orderId');
	
		$orderTables[] = array('table' => $resource->getTableName('log_quote'),
							   'where'=> "quote_id = ?",
							   'key' => 'quoteId');		
        
		$db =  $this->_db();
		
		$db->query("SET FOREIGN_KEY_CHECKS = 0");
		
		$errors = array();
		
		foreach($this->getRequest()->getPost('order_ids') as $orderId) {
			$quoteId = null;
			$order = Mage::getModel('sales/order')->load($orderId);
			if(($incrId = $order->increment_id)) {
				$query = $db->quoteInto("SELECT quote_id
									     FROM   " . $resource->getTableName('sales_flat_order') ."
									     WHERE entity_id = ?", $orderId);
				$result = $write->fetchAll($query);
				if(sizeof($result) > 0) {
					$quoteId = $result[0]['quote_id'];
				}
			}
			
			foreach($orderTables as $table) {
				if($table['table']) {
					$query = "DELETE FROM " . $table['table'] . "WHERE " . $table['where'];
					$query = $db->quoteInto($query, ${$table['key']});
					try {
						$db->query($query);
					} catch(Exception $e) {
						Mage::log('Kwetal_Delete_Orders: Query failed: ' , $query);
						$errors[] = $table['table'];
					}
				}
			}
		}
		$db->query("SET FOREIGN_KEY_CHECKS = 1");
		
		if(sizeof($errors) == 0) {
			$this->_getSession()->addSuccess($this->__('%s Order(s) deleted.',
													   sizeof($this->getRequest()->getPost('order_ids'))));
		} else {
			$this->_getSession()->addError($this->__('Order(s) error.'));
		}
			
		$this->_redirect('*/*/');		
    }
	
	protected function _findTables($orderTables)
	{
		$allTables = $this->_db()->fetchCol("SHOW TABLES");
		$ret = array();
		$maxIndex = sizeof($orderTable);
		for($i = 0; $i < $maxIndex; ++$i) {
			if(in_array($orderTables[$i]['table'], $allTables)) {
				$ret[] = $orderTables[$i];
			}
		}
		return $ret;
	}
	
	protected function _db()
	{
		if(! $this->_db) {
			$this->_db = Mage::getSingleton('core/resource')->getConnection('core_write');
		}
		return $this->_db;
	}
}
