<?php
/**
 * Zeon Solutions, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Zeon Solutions License
 * that is bundled with this package in the file LICENSE_ZE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.zeonsolutions.com/license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zeonsolutions.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to
 * newer versions in the future. If you wish to customize this extension for
 * your needs please refer to http://www.zeonsolutions.com for more information.
 *
 * @category    Zeon
 * @package     Zeon_CatalogManager
 * @author      Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 * @copyright   Copyright (c) 2014 Zeon Solutions, Inc. All Rights Reserved.
 *              (http://www.zeonsolutions.com)
 * @license     http://www.zeonsolutions.com/license/
 */
class Zeon_CatalogManager_Block_Adminhtml_Bestproducts_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('best_products');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);

        $this->setRowClickCallback('BestRowClick');
    }

    public function getProduct()
    {
        return Mage::registry('product');
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField(
                    'websites',
                    'catalog/product_website',
                    'website_id',
                    'product_id=entity_id',
                    null,
                    'left'
                );
            }
        }

        if ($column->getId() == "best_seller") {
            $productIds = $this->_getSelectedProducts();

            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()
                    ->addFieldToFilter(
                        'entity_id',
                        array('in' => $productIds)
                    );
            } elseif (!empty($productIds)) {
                $this->getCollection()
                    ->addFieldToFilter(
                        'entity_id',
                        array('nin' => $productIds)
                    );
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    protected function _prepareCollection()
    {
        $store = $this->_getStore();

        $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('sku')
                //->addAttributeToSelect('best_seller')
                //->addAttributeToSelect('best_seller_position')
                ->addAttributeToSelect('type_id')
                ->addAttributeToFilter('visibility', array('nin' => array(1, 3)));

        if ($store->getId()||$store->getId() == 0 ) {
            //$collection->setStoreId($store->getId());
            $collection->addStoreFilter($store);
            $collection->joinAttribute(
                'custom_name',
                'catalog_product/name',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'best_seller_position',
                'catalog_product/best_seller_position',
                'entity_id',
                null,
                'left',
                $store->getId()
            );
            $collection->joinAttribute(
                'best_seller',
                'catalog_product/best_seller',
                'entity_id',
                null,
                'left',
                $store->getId()
            );
            $collection->joinAttribute(
                'status',
                'catalog_product/status',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'visibility',
                'catalog_product/visibility',
                'entity_id',
                1,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'price',
                'catalog_product/price',
                'entity_id',
                null,
                'left',
                $store->getId()
            );
        } else {
            $collection->addAttributeToSelect('price');
            $collection->addAttributeToSelect('status');
            $collection->addAttributeToSelect('visibility');
        }

        $actionName = $this->getRequest()->getActionName();

        if ($actionName == 'exportCsv' || $actionName == 'exportXml') {
            $collection->addAttributeToFilter(
                'best_seller', array('eq' => true)
            );
        }

        $this->setCollection($collection);

        parent::_prepareCollection();

        $this->getCollection()->addWebsiteNamesToResult();

        return $this;
    }

    protected function _prepareColumns()
    {
        $actionName = $this->getRequest()->getActionName();
        $helper     = Mage::helper('zeon_catalogmanager');

        if ($actionName != 'exportCsv' && $actionName != 'exportXml') {

            $this->addColumn(
                'best_seller',
                array(
                    'header_css_class' => 'a-center',
                    'type'             => 'checkbox',
                    'name'             => 'best_seller',
                    'values'           => $this->_getSelectedProducts(),
                    'align'            => 'center',
                    'index'            => 'entity_id'
                )
            );
        }

        $this->addColumn(
            'entity_id',
            array(
                'header'   => $helper->__('ID'),
                'sortable' => true,
                'width'    => '60',
                'index'    => 'entity_id',
                'type'     => 'number'
            )
        );

        $renderer = 'adminhtml_popularproducts_renderer_name';
        $this->addColumn(
            'name',
            array(
                'header'   => $helper->__('Name'),
                'index'    => 'name',
                'renderer' => 'zeon_catalogmanager/' . $renderer,
            )
        );

        $this->addColumn(
            'type',
            array(
                'header'  => $helper->__('Type'),
                'width'   => '60px',
                'index'   => 'type_id',
                'type'    => 'options',
                'options' => Mage::getSingleton('catalog/product_type')
            ->getOptionArray(),
            )
        );

        $this->addColumn(
            'sku',
            array(
                'header' => $helper->__('SKU'),
                'width'  => '140',
                'index'  => 'sku'
            )
        );

        $renderer = 'adminhtml_popularproducts_renderer_visibility';
        $this->addColumn(
            'visibility',
            array(
                'header'   => $helper->__('Visibility'),
                'width'    => '140',
                'index'    => 'visibility',
                'filter'   => false,
                'renderer' => 'zeon_catalogmanager/' . $renderer,
            )
        );

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn(
                'websites',
                array(
                    'header'   => $helper->__('Websites'),
                    'width'    => '100px',
                    'sortable' => false,
                    'index'    => 'websites',
                    'type'     => 'options',
                    'options'  => Mage::getModel('core/website')
                ->getCollection()->toOptionHash(),
                )
            );
        }

        $store = $this->_getStore();
        $this->addColumn(
            'price',
            array(
                'header'        => $helper->__('Price'),
                'type'          => 'price',
                'currency_code' => $store->getBaseCurrency()->getCode(),
                'index'         => 'price',
            )
        );

        // Position column
        if (!$this->_isExport) {
            $this->addColumn(
                'best_seller_position',
                array(
                    'header'    => $helper->__('Position'),
                    'width'     => '1',
                    'type'      => 'number',
                    'index'     => 'best_seller_position',
                    'renderer'  => 'adminhtml/widget_grid_column_renderer_input',
                    'editable'  => true,
                )
            );
        } else {
            $this->addColumn(
                'best_seller_position',
                array(
                    'header'    => $helper->__('Position'),
                    'type'      => 'number',
                    'index'     => 'best_seller_position',
                )
            );
        }

        $this->addExportType(
            '*/*/exportCsv/type/best',
            $helper->__('CSV')
        );
        $this->addExportType(
            '*/*/exportXml/type/best',
            $helper->__('Excel XML')
        );

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true, 'blockname'=>'adminhtml_bestproducts_grid'));
    }

    protected function _getSelectedProducts($json = false)
    {
        $temp = $this->getRequest()->getPost('best_ids');
        $store = $this->_getStore();

        if ($temp) {
            parse_str($temp, $bestIds);
        }

        $_prod = Mage::getModel('catalog/product')->getCollection()
                ->joinAttribute(
                    'best_seller',
                    'catalog_product/best_seller',
                    'entity_id',
                    null,
                    'left',
                    $store->getId()
                )
                ->addAttributeToFilter('best_seller', '1');

        $products = $_prod->getColumnValues('entity_id');
        $selectedProducts = array();

        if ($json == true) {
            foreach ($products as $key => $value) {
                $selectedProducts[$value] = '1';
            }
            return Zend_Json::encode($selectedProducts);
        } else {
            foreach ($products as $key => $value) {
                if ((isset($bestIds[$value])) && ($bestIds[$value] == 0)) {
                } else {
                    $selectedProducts[$value] = '0';
                }
            }

            if (isset($bestIds)) {
                foreach ($bestIds as $key => $value) {
                    if ($value == 1) {
                        $selectedProducts[$key] = '0';
                    }
                }
            }

            return array_keys($selectedProducts);
        }

        return $products;
    }

    /**
     * add javascript before/after grid html
     */
    protected function _afterToHtml($html)
    {
        return $this->_prependHtml() .
            parent::_afterToHtml($html) .
            $this->_appendHtml();
    }

    private function _prependHtml()
    {
        $gridName = $this->getJsObjectName();

        $html = <<<EndHTML
                        <script type="text/javascript">
                        //<![CDATA[

                    categoryForm = new varienForm('best_edit_form');
                    categoryForm.submit= function (url) {

                    this._submit();
                        return true;
                    };

                    document.observe("dom:loaded", function() {
                        isCheckboxChecked();
                        setPositionValue();
                    });

                    function handleBestSellerPositionBlur(event, element, trElement)
                    {
                        var val = element.value;
                        var checkbox = Element.getElementsBySelector(
                                        trElement,
                                        'input.checkbox'
                                       ).first();

                        textBoxes.set(checkbox.value, val);
                        $("best_products_position").value = textBoxes.toQueryString();

                    }

                    function isCheckboxChecked()
                    {
                        var everycheckbox = $$("#best_products_table tbody input.checkbox");
                        everycheckbox.each(function(element, index) {
                            element.onclick = function(e)
                            {
                                var val = element.value;
                                if (element.checked) {
                                    element.checked = false;
                                    checkBoxes.set(val, 0);
                                } else {
                                    element.checked = "checked";
                                    checkBoxes.set(val, 1);
                                }
                            }
                        });
                    }

                    function setPositionValue()
                    {
                        var everytextbox = $$("#best_products_table tbody input.input-text");
                        everytextbox.each(function(element, index) {
                            element.onkeyup = function(event)
                            {
                                var trElement = Event.findElement(event, 'tr');
                                handleBestSellerPositionBlur(event, element, trElement)
                            }
                        });
                    }

                    function categorySubmit(url)
                    {
                        var params = {};
                        var fields = $('best_edit_form').getElementsBySelector(
                            'input', 'select'
                        );

                        categoryForm.submit();
                    }

                    function BestRowClick(grid, event)
                    {
                        isCheckboxChecked();
                        setPositionValue();
                        var trElement = Event.findElement(event, 'tr');
                        var isInput   = Event.element(event).tagName == 'INPUT';

                        var checkbox = Element.getElementsBySelector(
                            trElement,
                            'input.checkbox'
                        ).first();

                        // If the admin clicks on the text-box then return it.
                        if ('text' == Event.element(event).type) {
                            $("best_products_position").value = textBoxes.toQueryString();
                            return;
                        }

                        if(!checkbox) return;

                        var val = checkbox.value;

                        if (checkbox.checked) {
                            checkbox.checked = false;
                            checkBoxes.set(val, 0);
                        } else {
                            checkbox.checked = "checked";
                            checkBoxes.set(val, 1);
                        }

                        $("in_best_products").value = checkBoxes.toQueryString();

                           $gridName.reloadParams = {'best_ids':checkBoxes.toQueryString()};
                    }

                    /**
                     * The following code will execute if user navigate to pages
                     * and check/uncheck the checkbox.
                     */
                    var everycheckbox = $$("#best_products_table tbody input.checkbox");
                    everycheckbox.each(function(element, index) {
                        element.onclick = function(e)
                        {
                            var val = element.value;
                            if (element.checked) {
                                element.checked = false;
                                checkBoxes.set(val, 0);
                            } else {
                                element.checked = "checked";
                                checkBoxes.set(val, 1);
                        }
                    }
                });

            //]]>
                    </script>
EndHTML;
        return $html;
    }

    private function _appendHtml()
    {
        $html = '<script type="text/javascript">
        var checkBoxes = $H();
        var textBoxes  = $H();

        var checkbox_all = $$("#best_products_table thead input.checkbox").
            first();
        var everycheckbox = $$("#best_products_table tbody input.checkbox");

        checkbox_all.observe("click", function(event) {

        if (checkbox_all.checked) {
            everycheckbox.each(function(element, index) {
                checkBoxes.set(element.value, 1)
            });
        } else {
            everycheckbox.each(function(element, index) {
                checkBoxes.set(element.value, 0)
            });
        }
        $("in_best_products").value = checkBoxes.toQueryString();
        });

        </script>';

        return $html;
    }

    /**
     * Retrieve a file container array by grid data as CSV
     *
     * Return array with keys type and value
     *
     * @return array

    public function getCsvFile()
    {
        $this->_isExport = true;
        parent::getCsvFile();
    }

    public function getXml()
    {
        $this->_isExport = true;
        parent::getXml();

    }  */

}