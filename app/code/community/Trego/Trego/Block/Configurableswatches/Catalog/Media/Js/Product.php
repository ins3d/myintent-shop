<?php
class Trego_Trego_Block_Configurableswatches_Catalog_Media_Js_Product extends Mage_ConfigurableSwatches_Block_Catalog_Media_Js_Product {
    public function getProductImageFallbacks($keepFrame = null) {
        /* @var $helper Mage_ConfigurableSwatches_Helper_Mediafallback */
        $helper = Mage::helper('trego/mediafallback');

        $fallbacks = array();
		$store = Mage::app()->getStore();
		$code  = $store->getCode();

        $products = $this->getProducts();

		$keepFrame = false;//Mage::getStoreConfig("trego_settings/product_view/aspect_ratio",$code);
		$ratio_width = Mage::getStoreConfig("trego_settings/product_view/ratio_width",$code);
		$ratio_height = Mage::getStoreConfig("trego_settings/product_view/ratio_height",$code);
        /* @var $product Mage_Catalog_Model_Product */
        foreach ($products as $product) {
			$imageFallback = $helper->getConfigurableImagesFallbackArray($product, $this->_getImageSizes(), false, $ratio_width, $ratio_height);
            $fallbacks[$product->getId()] = array(
                'product' => $product,
                'image_fallback' => $this->_getJsImageFallbackString($imageFallback)
            );
        }

        return $fallbacks;
    }
}
?>