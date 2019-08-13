<?php
class Aaryasolutions_Dailydeals_Adminhtml_DailydealsController extends Mage_Adminhtml_Controller_Action {
	public function indexAction() {
		$this->_title ( $this->__ ('Daily deals') )->_title ( $this->__ ( 'Daily deals' ) );
		$this->loadLayout ();
		$this->_setActiveMenu ('aarayasolutions');
		$this->_addContent ( $this->getLayout ()->createBlock ( 'dailydeals/adminhtml_dailydeals_deals' ) );
		$this->renderLayout ();
	}
	public function gridAction() {
		$this->loadLayout ();
		$this->getResponse ()->setBody ( $this->getLayout ()->createBlock ('dailydeals/adminhtml_dailydeals_deals_grid' )->toHtml () );
	}
	public function saveAction() {
		$dates = Mage::app ()->getrequest ()->getPost ('date_selected' );
		
		
		$bb = new Aaryasolutions_Dailydeals_Block_Adminhtml_Dailydeals_Deals_Grid ();
		$previousarray = $bb->_getSelectedProducts ();
		
		$nowarray = explode ( ',', Mage::app ()->getrequest ()->getPost ('main_product_select_dailydeals' ));

		
		foreach ( $previousarray as $pr_id ) {
			
			if (in_array ( $pr_id, $nowarray )) {
			} else {
				
				Mage::getModel ('dailydeals/dailydeals' )->deleteDailyDeal ( $pr_id );
			}
		}
		
		foreach ($dates as $productid => $selectedarray ) {
			
			$ifproductExists = Mage::getModel ('dailydeals/dailydeals' )->ifProductIdExists ( $productid );
			
			$data ['dailydeals_date'] = $selectedarray [0];
			$data ['dailydeals_product'] = $productid;
			
			if (! is_null ( $ifproductExists )) {
				
				$dailydealsId = $ifproductExists;
				
				Mage::getModel ('dailydeals/dailydeals' )->updateDailyDeals ( $data, $dailydealsId );
			} else {
				Mage::getModel ( 'dailydeals/dailydeals' )->addDailyDeals ( $data );
			}
		}
		
		$this->_redirect ('dailydeals/adminhtml_dailydeals' );
	}

	public function exportCsvAction()
	{
	    $fileName   = 'dailydeals.csv';
	    $content    = $this->getLayout()->createBlock('dailydeals/adminhtml_dailydeals_deals_grid')->getCsvFile();
	    $this->_prepareDownloadResponse($fileName, $content);
	}

	protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
	{
	$this->_prepareDownloadResponse($fileName, $content, $contentType);
	}
}