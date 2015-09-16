<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Sales Order Invoice Pdf default items renderer
 *
 * @category   Mage
 * @package    Mage_Sales
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Mage_Sales_Model_Order_Pdf_Items_Invoice_Default extends Mage_Sales_Model_Order_Pdf_Items_Abstract
{
    /**
     * Draw item line
     */
    public function draw()
    {
        $order  = $this->getOrder();
        $item   = $this->getItem();
        $pdf    = $this->getPdf();
        $page   = $this->getPage();
        $lines  = array();
		
        // draw Product name
        $lines[0] = array(array(
            'text' => Mage::helper('core/string')->str_split($item->getName(), 35, true, true),
/* customization begin */
			'font' => 'bold',
/* customization end */
            'feed' => 35,
        ));

        // draw SKU
        $lines[0][] = array(
            'text'  => Mage::helper('core/string')->str_split($this->getSku($item), 17),			
/* customization						
            'feed'  => 290
            'align' => 'right'
*/
/* customization begin */
            'feed'  => 345
/* customization end */
        );

        // draw QTY
        $lines[0][] = array(
            'text'  => $item->getQty() * 1,
/* customization
            'feed'  => 435
            'align' => 'right'
*/
/* customization begin */
            'feed'  => 488
/* customization end */
        );

        // draw item Prices
        $i = 0;
        $prices = $this->getItemPricesForDisplay();
/* customization		
        $feedPrice = 395;
        $feedSubtotal = $feedPrice + 170;
*/
/* customization begin */
        $feedPrice = 435;
        $feedSubtotal = $feedPrice + 130;
/* customization end */
	
        foreach ($prices as $priceData){
            if (isset($priceData['label'])) {
                // draw Price label
                $lines[$i][] = array(
                    'text'  => $priceData['label'],
                    'feed'  => $feedPrice
/* customization			
            'align' => 'right'
*/
                );
                // draw Subtotal label
                $lines[$i][] = array(
                    'text'  => $priceData['label'],
                    'feed'  => $feedSubtotal
/* customization			
            'align' => 'right'
*/
                );
                $i++;
            }
            // draw Price
            $lines[$i][] = array(
                'text'  => $priceData['price'],
                'feed'  => $feedPrice
/* customization							
                'font'  => 'bold',
				'align' => 'right'
*/
            );
            // draw Subtotal
            $lines[$i][] = array(
                'text'  => $priceData['subtotal'],
                'feed'  => $feedSubtotal,
                'font'  => 'bold',
                'align' => 'right'
            );
            $i++;
        }
		
/* customization
        // draw Tax
        $lines[0][] = array(
            'text'  => $order->formatPriceTxt($item->getTaxAmount()),
            'feed'  => 495,
            'font'  => 'bold',
            'align' => 'right'
        );
*/

        // custom options
        $options = $this->getItemOptions();
		
        if ($options) {
/* customization begin - forcing invoice to print out options in the following order: "String Color", "Circle Color", "Your Word", "Story" 
	Magento 1.9 error - prints options based on primary ID instead of sort ID - http://magento.stackexchange.com/questions/45396/magento-1-9-1-configurable-product-attribute-sorting
*/							
			$optionsArray = array('String Color', 'Circle Color', 'Your Word', 'Story');
			
			foreach ($optionsArray as $forcedOption)
			{
/* customization end */
			
				foreach ($options as $option) {
/* customization begin */				
					$option['label'] = str_replace(" (optional)", "", $option['label']);
					if ($option['label'] == $forcedOption)
					{	
/* customization end */

						// draw options label
						$lines[][] = array(
/* customization				
							'text' => Mage::helper('core/string')->str_split(strip_tags($option['label']), 40, true, true),
							'font' => 'italic',
							'feed' => 35
*/
/* customization begin */
							'text' => Mage::helper('core/string')->str_split(strip_tags($option['label']).":", 55, true, true),
							'feed' => 45,
							'font' => 'bold'
/* customization end */

						);

/* customization begin */
						switch ($option['label']) {
							case 'Your Word':
								$xAlign = 60;
								break;
							case 'String Color':
								$xAlign = 65;
								break;						
							case 'Circle Color':
								$xAlign = 65;
								break;
							case 'Story':
								$xAlign = 10;
								break;
							default:	
								$xAlign = 5;
								break;
						}
/* customization end */
					
						if ($option['value']) {
							if (isset($option['print_value'])) {
								$_printValue = $option['print_value'];
							} else {
								$_printValue = strip_tags($option['value']);
							}
							$values = explode(', ', $_printValue);
							foreach ($values as $value) {
								
								$lines[][] = array(
/* customization							
									'text' => Mage::helper('core/string')->str_split($value, 30, true, true),
									'feed' => 40
*/
/* customization begin */
									'text' => Mage::helper('core/string')->str_split($value, 60, true, true),
									'feed' => 45 + $xAlign
/* customization end */							
								);
							}
						}
/* customization begin */									
					}
/* customization end */														
				}
/* customization begin */
				unset($option); // break the reference with the last element
			}	
/* customization end */														
        }


/* customization begin - add line break between product items */
$lines[10][0] = array('text' => " ", 'feed' => 35);
/* customization end */
		
        $lineBlock = array(
            'lines'  => $lines,
/* customization
            'height' => 20
*/
/* customization begin */
            'height' => 15
/* customization end */										
        );

/* customization
        $page = $pdf->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
*/
/* customization begin - keeps option label and value on same line in product details section of invoice */
        $page = $pdf->drawProductDetails($page, array($lineBlock), array('table_header' => true));
/* customization end */
        $this->setPage($page);
    }
}
