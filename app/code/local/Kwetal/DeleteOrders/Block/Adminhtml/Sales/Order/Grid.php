<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0).
 * It is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */
class Kwetal_DeleteOrders_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();
	
        $this->getMassactionBlock()->addItem('delete_order', array('label'=> Mage::helper('sales')->__('Delete order'),
                                                                   'url'  => $this->getUrl('*/sales_order/deleteorder')));	
        return $this;
    }
}
