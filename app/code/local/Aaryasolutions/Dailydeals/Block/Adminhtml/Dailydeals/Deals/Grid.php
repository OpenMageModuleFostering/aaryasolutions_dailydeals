<?php
class Aaryasolutions_Dailydeals_Block_Adminhtml_Dailydeals_Deals_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'dailydeals_deals_grid' );
		$this->setDefaultSort ( 'dailydeals_id' );
		$this->setDefaultDir ( 'DESC' );
		$this->setSaveParametersInSession ( true );
		$this->setUseAjax ( true );
		
		$this->settemplate ('dailydeals/grid.phtml' );
	}
	protected function _getProduct() {
		return Mage::getModel ('catalog/product')->load (5);
	}
	protected function _addColumnFilterToCollection($column) {
		
		// Set custom filter for in product flag
		if ($column->getId () == 'in_products') {
			$productIds = $this->_getSelectedProducts ();
			if (empty ( $productIds )) {
				$productIds = 0;
			}
			if ($column->getFilter ()->getValue ()) {
				$this->getCollection ()->addFieldToFilter ( 'entity_id', array (
						'in' => $productIds 
				) );
			} else {
				if ($productIds) {
					$this->getCollection ()->addFieldToFilter ( 'entity_id', array (
							'nin' => $productIds 
					) );
				}
			}
		} elseif ($column->getId () == 'dailydeals_date') {
			
			$val = $column->getFilter ()->getValue ();
			
			if ($val) {
				
				$from = date ( "Y-m-d", strtotime ( $val ['orig_from'] ) );
				$to = date ( "Y-m-d", strtotime ( $val ['orig_to'] ) );
				
				$this->getCollection ()->getSelect ()->where ( "dl.dailydeals_date <= '" . $to . "' AND dl.dailydeals_date >= '" . $from . "'" );
			}
			
			// code...
		} else {
			
			parent::_addColumnFilterToCollection ( $column );
		}
		
		return $this;
	}
	protected function _prepareCollection() {
		$collection = Mage::getResourceModel ( 'catalog/product_collection' )->

		addAttributeToSelect ( '*' );
		
		$productIds = $this->_getSelectedProducts ();
		if (empty ( $productIds )) {
			$productIds = array (
					0 
			);
		}
		// $collection->addFieldToFilter('entity_id', array('in' => $productIds));
		
		$collection->getSelect ()->joinLeft ( array (
				'dl' => 'dailydeals' 
		), 'e.entity_id = dl.dailydeals_product', array (
				'dl.*' 
		) );
		
		$this->setCollection ( $collection );
		return parent::_prepareCollection ();
	}
	public function getSelectedproductsIdsString() {
		$selectedIds = $this->_getSelectedProducts ();
		return implode ( ',', $selectedIds );
	}
	public function _getSelectedProducts() {
		$prepareSelect = array ();
		$selectedproducts = Mage::getModel ('dailydeals/dailydeals')->getCollection ();
		foreach ( $selectedproducts as $rr ) {
			
			array_push ( $prepareSelect, $rr->getdailydeals_product () );
		}
		
		return $prepareSelect;
	}
	protected function _myCustomFilter($collection, $column) {
		if ($value == 0) {
			
			$collection1 = Mage::getModel ( 'cataloginventory/stock' )->getItemCollection ()->addFieldToFilter ( 'is_in_stock' );
			
			$product_ids = array ();
			foreach ( $collection1 as $item ) {
				$product_ids [] = $item->getProductId ();
			}
			
			$collection->addFieldToFilter ( 'entity_id', array (
					'in' => $product_ids 
			) );
			// out of stock
		} elseif ($value == 1) {
			
			Mage::getSingleton ( 'cataloginventory/stock' )->addInStockFilterToCollection ( $collection );
			
			// code...
		}
		
		return $this;
	}
	protected function _prepareColumns() {
		$helper = Mage::helper ( 'dailydeals' );
		
		$this->addColumn ( 'in_products', array (
				'header_css_class' => 'a-center',
				'type' => 'checkbox',
				'name' => 'in_products',
				'values' => $this->_getSelectedProducts (),
				'align' => 'center',
				'index' => 'entity_id' 
		) );
		
		
		$this->addColumn ('dailydeals_date', array (
				'header' => Mage::helper ( 'catalog' )->__ ( 'Date Selected' ),
				'name' => 'position',
				'type' => 'date',
				'validate_class' => 'validate-number',
				'index' => 'dailydeals_date',
				'width' => 200,
				'editable' => ! $this->_getProduct ()->getRelatedReadonly (),
				'edit_only' => ! $this->_getProduct ()->getId (),
				
				'renderer' => 'dailydeals/adminhtml_dailydeals_deals_renderer_dealsrenderer' 
		)
		 );
		
		$this->addColumn ( 'entity_id', array (
				'header' => Mage::helper ( 'catalog' )->__ ( 'Product ID' ),
				'sortable' => true,
				'width' => 60,
				'index' => 'entity_id' 
		) );
		
		$this->addColumn ( 'name', array (
				'header' => Mage::helper ( 'catalog' )->__ ( 'Name' ),
				'index' => 'name' 
		) );
		
		$this->addColumn ( 'entity_id', array (
				'header' => Mage::helper ( 'catalog' )->__ ( 'Stock' ),
				'index' => 'stock_check',
				'type' => 'options',
				'renderer' => 'dailydeals/adminhtml_dailydeals_deals_renderer_dealsrenderer',
				'filter_condition_callback' => array (
						$this,
						'_myCustomFilter' 
				),
				'options' => array (
						0 => 'Out of Stock',
						1 => 'In Stock' 
				) 
		)
		 );
		
		$this->addExportType('*/*/exportCsv',
         Mage::helper('dailydeals')->__('CSV'));

		
		
		return parent::_prepareColumns ();
	}
	public function getGridUrl() {
		return $this->getUrl ( '*/*/grid', array (
				'_current' => true 
		) );
	}
}