<?php

namespace Rosenborg\ProductMembership\Test\Model\Carrier;

use Rosenborg\ProductMembership\Model\Carrier\Flatrate;

use PHPUnit\Framework\TestCase;

/**
 * Class FlatrateTest
 * @package Rosenborg\ProductMembership\Test\Model\Carrier
 */
class FlatrateTest extends TestCase
{
    protected $flatrate;
    protected $scopeConfig;
    protected $rateErrorFactory;
    protected $logger;
    protected $rateResultFactory;
    protected $rateMethodFactory;
    protected $itemPriceCalculator;
    protected $rateRequestMock;
    protected $itemMock;
    protected $productMock;

    public function setUp()
    {

        $this->scopeConfig = $this->getMockBuilder('\Magento\Framework\App\Config\ScopeConfigInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->rateErrorFactory = $this->getMockBuilder('\Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->logger = $this->getMockBuilder('\Psr\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->itemPriceCalculator = $this->getMockBuilder('\Magento\OfflineShipping\Model\Carrier\Flatrate\ItemPriceCalculator')
            ->disableOriginalConstructor()
            ->getMock();

        $this->rateResultFactory = $this->getMockBuilder('\Magento\Shipping\Model\Rate\ResultFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->rateMethodFactory = $this->getMockBuilder('\Magento\Quote\Model\Quote\Address\RateResult\MethodFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->rateRequestMock = $this->getMockBuilder('\Magento\Quote\Model\Quote\Address\RateRequest')
            ->disableOriginalConstructor()
            ->setMethods(['getAllItems'])
            ->getMock();

        $this->itemMock = $this->getMockBuilder('\Magento\Quote\Model\Quote\Item')
            ->disableOriginalConstructor()
            ->getMock();

        $this->productMock = $this->getMockBuilder('\Rosenborg\ProductMembership\Model\Product')
            ->disableOriginalConstructor()
            ->getMock();


        $this->flatrate = new Flatrate(
            $this->scopeConfig,
            $this->rateErrorFactory,
            $this->logger,
            $this->rateResultFactory,
            $this->rateMethodFactory,
            $this->itemPriceCalculator,
            []
        );
    }

    public function testZeroShippingPrice()
    {

        $this->productMock->expects($this->any())
            ->method('getIsWithMembership')
            ->will($this->returnValue(true));

        $this->itemMock->expects($this->any())
            ->method('getProduct')
            ->will($this->returnValue($this->productMock));

        // returns mock \Magento\Quote\Model\Quote\Item in array
        $this->rateRequestMock->expects($this->any())
            ->method('getAllItems')
            ->will($this->returnValue([$this->itemMock]));

        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(5));

        $this->flatrate->setDecorate(false);
        $this->assertEquals(0, $this->flatrate->collectRates($this->rateRequestMock));
    }

    public function testFiveShippingPrice()
    {

        $this->productMock->expects($this->any())
            ->method('getIsWithMembership')
            ->will($this->returnValue(false));

        $this->itemMock->expects($this->any())
            ->method('getProduct')
            ->will($this->returnValue($this->productMock));

        // returns mock \Magento\Quote\Model\Quote\Item in array
        $this->rateRequestMock->expects($this->any())
            ->method('getAllItems')
            ->will($this->returnValue([$this->itemMock]));

        $this->scopeConfig->expects($this->any())
            ->method('getValue')
            ->will($this->returnValue(5));

        $this->flatrate->setDecorate(false);
        $this->assertEquals(5, $this->flatrate->collectRates($this->rateRequestMock));
    }
}