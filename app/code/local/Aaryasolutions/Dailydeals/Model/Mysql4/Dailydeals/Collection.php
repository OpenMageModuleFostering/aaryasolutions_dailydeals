<?php

class Aaryasolutions_Dailydeals_Model_Mysql4_Dailydeals_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('dailydeals/dailydeals');
    }

	
}