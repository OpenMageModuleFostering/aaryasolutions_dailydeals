<?php
class Aaryasolutions_Dailydeals_Block_Adminhtml_Dailydeals_Deals_Renderer_Dealsrenderer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action {
	public function render(Varien_Object $row) {
		if ($this->getColumn ()->getIndex () == 'dailydeals_date') {
			return $this->_getValue ( $row );
		} elseif ($this->getColumn ()->getIndex () == 'stock_check') {
			
			$val = $row->getData ( 'entity_id' );
			
			$product = Mage::getModel ( 'catalog/product' )->load ( $val );
			$stock = $product->getStockItem ();
			if ($stock->getIsInStock ()) {
				return "IN STOCK";
			} else {
				return "OUT OF STOCK";
			}
			
			// code...
		}
	}
	public function _getValue(Varien_Object $row) {
		$val = $row->getData ( 'entity_id' );
		
		$getDailyDate = Mage::getModel ( 'dailydeals/dailydeals' )->getDailyDealsDateByProductId ( $val );
		
		if (! is_null ( $getDailyDate )) {
			return $getDailyDate;
		} else {
			
			return '-';
		}
	}
}