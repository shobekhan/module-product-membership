<?php

namespace Rosenborg\ProductMembership\Model;

use Magento\Framework\Exception\LocalizedException;

class Cart extends \Magento\Checkout\Model\Cart
{

    /**
     * @param int|\Magento\Catalog\Model\Product $productInfo
     * @param null $requestInfo
     * @return $this|\Magento\Checkout\Model\Cart
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addProduct($productInfo, $requestInfo = null)
    {
        if ($productInfo->getIsWithMembership() && !$this->_customerSession->isLoggedIn()) {
            throw new LocalizedException(__('You are not logged in'));
        }
        parent::addProduct($productInfo, $requestInfo);
        return $this;
    }
}