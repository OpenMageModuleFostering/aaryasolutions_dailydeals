<?php
class Aaryasolutions_Dailydeals_Block_Dealsfront extends Mage_Core_Block_Template {
	protected function _construct() {
		parent::_construct ();
		$this->_blockGroup = 'dailydeals';
		
	}
	protected function _toHtml() {
		return parent::_toHtml ();
	}
	public function getToShowProductId() {
		return Mage::getModel ('dailydeals/dailydeals')->getProductIdForHomepage ();
	}
}