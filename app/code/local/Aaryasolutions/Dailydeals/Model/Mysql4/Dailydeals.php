<?php

class Aaryasolutions_Dailydeals_Model_Mysql4_Dailydeals extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the dailydeals_id refers to the key field in your database table.
        $this->_init('dailydeals/dailydeals', 'dailydeals_id');
        
       
    }

	

}