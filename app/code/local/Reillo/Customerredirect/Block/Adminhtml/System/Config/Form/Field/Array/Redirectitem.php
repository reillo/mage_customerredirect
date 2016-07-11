<?php

class Reillo_Customerredirect_Block_Adminhtml_System_Config_Form_Field_Array_Redirectitem extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
	protected $group_options;
	protected $url_options;

	public function __construct() {
		// create columns
		$this->addColumn('customer_group', array(
			'label' => Mage::helper('adminhtml')->__('Group'),
			'size' => 16,
		));
		$this->addColumn('to', array(
			'label' => Mage::helper('adminhtml')->__('-->'),
			'size' => 10
		));
		$this->addColumn('store', array(
			'label' => Mage::helper('adminhtml')->__('Website'),
			'size' => 50
		));
		$this->addColumn('uri_path', array(
			'label' => Mage::helper('adminhtml')->__('URI Path'),
			'size' => 20
		));
		$this->_addAfter = false;
		$this->_addButtonLabel = Mage::helper('adminhtml')->__('Add Redirect item');

		$this->setTemplate('reillo/customerredirect/system/config/form/field/array_select.phtml');
		$this->group_options = Mage::getSingleton('adminhtml/system_config_source_customer_group')->toOptionArray();
		$this->url_options = Mage::getSingleton('adminhtml/system_config_source_store')->toOptionArray();

		parent::__construct();
	}

	protected function _renderCellTemplate($columnName) {
		if (empty($this->_columns[$columnName])) {
			throw new Exception('Wrong column name specified.');
		}
		$column = $this->_columns[$columnName];
		$inputName = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';

		if ($columnName == 'customer_group' || $columnName == 'store') {
			$options = $columnName == 'customer_group' ? $this->group_options : $this->url_options;
			$rendered = '<select name="' . $inputName . '" style="width:160px;"> ';
			foreach ($options as $key => $item) {
				$rendered .= '<option value="' . $item['value'] . '">' . addslashes($item['label']) . '</option>';
			}
			$rendered .= '</select>';
		} else if ($columnName == 'to') {
			return "-->";
		} else {
			return '<input type="text" name="' . $inputName . '" value="#{' . $columnName . '}" ' . ($column['size'] ? 'size="' . $column['size'] . '"' : '') . '/>';
		}

		return $rendered;
	}
}


