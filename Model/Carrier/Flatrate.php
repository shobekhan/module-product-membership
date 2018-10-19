<?php

namespace Rosenborg\ProductMembership\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Item;

/**
 * Class Flatrate
 * @package Rosenborg\ProductMembership\Model\Carrier
 */
class Flatrate extends \Magento\OfflineShipping\Model\Carrier\Flatrate
{
    protected $decorate;

    /**
     * @return bool
     */
    public function getDecorate()
    {
        if (null === $this->decorate) {
            $this->decorate = true;
        }
        return $this->decorate;
    }

    /**
     * @param mixed $decorate
     */
    public function setDecorate($decorate)
    {
        $this->decorate = $decorate;
    }

    /**
     * @param RateRequest $request
     * @return bool|false|int|\Magento\Shipping\Model\Rate\Result|Result|string
     */
    public function collectRates(RateRequest $request)
    {
        $items = $request->getAllItems();
        $hasProductMembership = false;

        /** @var Item $item */
        foreach ($items as $item) {
            if ($item->getProduct()->getIsWithMembership()) {
                $hasProductMembership = true;
            }
        }

        if ($hasProductMembership) {
            $shippingPrice = 0;
        } else {
            $shippingPrice = $this->getConfigData('price'); // return default flat rate price
        }

        if (false === $this->getDecorate()) {
            return $shippingPrice;
        }

        /** @var Result $result */
        $result = $this->_rateResultFactory->create();

        if ($shippingPrice !== false) {
            $method = $this->createResultMethod($shippingPrice);
            $result->append($method);
        }

        return $result;
    }

    /**
     * @param int|float $shippingPrice
     * @return \Magento\Quote\Model\Quote\Address\RateResult\Method
     */
    private function createResultMethod($shippingPrice)
    {
        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();

        $method->setCarrier('flatrate');
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod('flatrate');
        $method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);
        return $method;
    }

}