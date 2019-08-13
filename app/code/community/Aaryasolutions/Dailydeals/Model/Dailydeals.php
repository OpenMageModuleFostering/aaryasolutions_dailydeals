<?php
class Aaryasolutions_Dailydeals_Model_dailydeals extends Mage_Core_Model_Abstract {
	public function _construct() {
		parent::_construct ();
		$this->_init ('dailydeals/dailydeals');
	}
	public function getDailyDealsDateByProductId($pid) {
		$toreturn = null;
		$collection = $this->getCollection ();
		$collection->addFieldToFilter ('dailydeals_product', array (
				'eq' => $pid 
		) );
		
		if ($collection->count () == 1) {
			
			foreach ( $collection as $rr ) {
				$toreturn = $rr->getdailydeals_date ();
			}
		}
		
		return $toreturn;
	}
	public function getNearestDateFromToday() {
		$resource = Mage::getSingleton ( 'core/resource' );
		$readConnection = $resource->getConnection ( 'core_read' );
		$table = 'dailydeals';
		
		$query = "SELECT * FROM {$table} WHERE dailydeals_date < now() ORDER BY ABS( DATEDIFF( NOW( ) , dailydeals_date ) ) LIMIT 1";
		$results = $readConnection->fetchAll ( $query );
		
		foreach ( $results as $result ) {
			
			return $result ['dailydeals_product'];
		}
	}
	public function getProductIdForHomepage() {
		$toreturn = null;
		$currentDate = Mage::getModel ( 'core/date' )->date ( 'Y-m-d' );
		
		$collection = $this->getCollection ();
		$collection->addFieldToFilter ( 'dailydeals_date', array (
				'eq' => $currentDate 
		) );
		
		if ($collection->count () > 0) {
			
			foreach ( $collection as $rr ) {
				$toreturn = $rr->getdailydeals_product ();
			}
		} else {
			
			$toreturn = $this->getNearestDateFromToday ();
		}
		
		return $toreturn;
	}
	public function deleteDailyDeal($pid) {
		$resource = Mage::getSingleton ( 'core/resource' );
		
		/**
		 * Retrieve the write connection
		 */
		$writeConnection = $resource->getConnection ( 'core_write' );
		
		/**
		 * Retrieve our table name
		 */
		$table = 'dailydeals';
		
		$query = "DELETE FROM {$table} WHERE dailydeals_product = " . ( int ) $pid;
		
		$writeConnection->query ( $query );
	}
	public function ifProductIdExists($pid) {
		$toreturn = null;
		$collection = $this->getCollection ();
		$collection->addFieldToFilter ( 'dailydeals_product', array (
				'eq' => $pid 
		) );
		
		if ($collection->count () == 1) {
			
			foreach ( $collection as $rr ) {
				$toreturn = $rr->getdailydeals_id ();
			}
		}
		
		return $toreturn;
	}
	public function addDailyDeals($data) {
		$model = $this->setData ( $data );
		try {
			$insertId = $model->save ()->getId ();
			
		} catch ( Exception $e ) {
			echo $e->getMessage ();
		}
	}
	function updateDailyDeals($data, $id) {
		$model = $this->load ( $id )->addData ( $data );
		try {
			$model->setId ( $id )->save ();
			
		} catch ( Exception $e ) {
			// echo $e->getMessage();
		}
	}
}