<?php
 
class Aaryasolutions_Dailydeals_Block_Adminhtml_Dailydeals_Deals extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'dailydeals';
        $this->_controller = 'adminhtml_dailydeals_deals';
        $this->_headerText = Mage::helper('dailydeals')->__('Daily Deals');
 
        parent::__construct();
        $this->_removeButton('add');
        
    }



}