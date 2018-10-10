<?php

namespace Rosenborg\ProductMembership\Model;

/**
 * Class Product
 * @package Rosenborg\ProductMembership\Model
 */
class Product extends \Magento\Catalog\Model\Product
{
    /**
     * Get product name
     *
     * @return string
     * @codeCoverageIgnoreStart
     */
    public function getName()
    {
        return $this->_getData(self::NAME) . ' saas';
    }
}