<?php

namespace Rosenborg\ProductMembership\Model;

/**
 * Class Product
 * @package Rosenborg\ProductMembership\Model
 */
class Product extends \Magento\Catalog\Model\Product
{

    /**
     * @return bool
     */
    public function getIsWithMembership()
    {
        if ('1' === $this->_getData('with_membership')) {
            return true;
        } else {
            return false;
        }
    }


}