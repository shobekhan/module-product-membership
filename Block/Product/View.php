<?php

namespace Rosenborg\ProductMembership\Block\Product;

use Rosenborg\ProductMembership\Model\Product;

/**
 * Class View
 * @package Rosenborg\ProductMembership\Block\Product
 */
class View extends \Magento\Catalog\Block\Product\View
{

    protected $sessionFactory;
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        array $data = [],
        \Magento\Customer\Model\SessionFactory $sessionFactory
    ) {
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
        $this->sessionFactory = $sessionFactory;
    }

    /**
     * @return bool
     */
    public function canUserBuyProduct()
    {
        $customerSession = $this->sessionFactory->create();
        /** @var Product $product */
        $product = $this->getProduct();
        if (false === $product->getIsWithMembership()) {
            return true;
        } elseif (true === $product->getIsWithMembership() && true === $customerSession->isLoggedIn()) {
            return true;
        } else {
            return false;
        }
    }

}