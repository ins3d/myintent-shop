<?php

class Trego_Trego_Helper_Cssgen extends Mage_Core_Helper_Abstract
{
	protected $_generatedCssFolder;
	protected $_generatedCssPath;
	protected $_generatedCssDir;
	
	public function __construct()
	{
		$this->_generatedCssFolder = 'css/configed/';
		$this->_generatedCssPath = 'frontend/default/trego/' . $this->_generatedCssFolder;
		$this->_generatedCssDir = Mage::getBaseDir('skin') . '/' . $this->_generatedCssPath;
	}
	
	public function getGeneratedCssDir()
    {
        return $this->_generatedCssDir;
    }

	public function getSettingsFile()
	{
		return $this->_generatedCssFolder . 'settings_' . Mage::app()->getStore()->getCode() . '.css';
	}
	
	public function getDesignFile()
	{
		return $this->_generatedCssFolder . 'design_' . Mage::app()->getStore()->getCode() . '.css';
	}
}
