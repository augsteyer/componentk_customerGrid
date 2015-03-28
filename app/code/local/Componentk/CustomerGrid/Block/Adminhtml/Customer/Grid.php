<?php
 /**
 * @company ComponentK
 * @author Aug Steyer <augsteyer@gmail.com>
 * Created: 3/24/2015 | 19:41
 */ 
class Componentk_CustomerGrid_Block_Adminhtml_Customer_Grid extends Mage_Adminhtml_Block_Customer_Grid {

	/**
	 * Lightening up the collection call
	 * @return $this
	 * @throws \Mage_Core_Exception
	 */
	protected function _prepareCollection()
	{
		$collection = Mage::getResourceModel('customer/customer_collection')
		                  ->addNameToSelect()
		                  ->addAttributeToSelect('email')
		                  ->addAttributeToSelect('created_at')
		                  ->addAttributeToSelect('group_id');
		$this->setCollection($collection);

		$this->_preparePage();

		$columnId = $this->getParam($this->getVarNameSort(), $this->_defaultSort);
		$dir      = $this->getParam($this->getVarNameDir(), $this->_defaultDir);
		$filter   = $this->getParam($this->getVarNameFilter(), null);

		if (is_null($filter)) {
			$filter = $this->_defaultFilter;
		}

		if (is_string($filter)) {
			$data = $this->helper('adminhtml')->prepareFilterString($filter);
			$this->_setFilterValues($data);
		}
		else if ($filter && is_array($filter)) {
			$this->_setFilterValues($filter);
		}
		else if(0 !== sizeof($this->_defaultFilter)) {
			$this->_setFilterValues($this->_defaultFilter);
		}

		if (isset($this->_columns[$columnId]) && $this->_columns[$columnId]->getIndex()) {
			$dir = (strtolower($dir)=='desc') ? 'desc' : 'asc';
			$this->_columns[$columnId]->setDir($dir);
			$this->_setCollectionOrder($this->_columns[$columnId]);
		}

		if (!$this->_isExport) {
			$this->getCollection()->load();
			$this->_afterLoadCollection();
		}

		return $this;
	}

	protected function _prepareColumns()
	{
		parent::_prepareColumns();
		$this->removeColumn('Telephone');
		$this->removeColumn('billing_postcode');
		$this->removeColumn('billing_country_id');
		$this->removeColumn('billing_region');
	}
}