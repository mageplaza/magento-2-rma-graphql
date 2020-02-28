<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_RMAGraphQl
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

declare(strict_types=1);

namespace Mageplaza\RMAGraphQl\Model\Resolver;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\CurrencyFormatter\Plugin\Sale\Order;
use Mageplaza\RMA\Helper\Data;
use Magento\Cms\Model\Template\FilterProvider;
use Mageplaza\RMA\Model\Api\RequestManagement;
use Mageplaza\RMA\Model\Request\ReplyFactory;
use Mageplaza\RMA\Model\RequestFactory;
use Mageplaza\RMA\Model\Request\ItemFactory;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * Class RMADataProvider
 * @package Mageplaza\RMAGraphQl\Model\Resolver
 */
abstract class AbstractRMARequest implements ResolverInterface
{
    /**
     * @var Data
     */
    protected $helperData;

    protected $requestManagement;

    protected $replyFactory;

    protected $requestFactory;

    protected $itemFactory;

    protected $getCustomer;

    protected $order;

    /**
     * SizeChartDataProvider constructor.
     *
     * @param Data $helperData
     */
    public function __construct(
        Data $helperData,
        RequestManagement $requestManagement,
        ReplyFactory $replyFactory,
        RequestFactory $requestFactory,
        ItemFactory $itemFactory,
        GetCustomer $getCustomer,
        OrderInterface $order
    ) {
        $this->helperData     = $helperData;
        $this->requestManagement = $requestManagement;
        $this->replyFactory = $replyFactory;
        $this->requestFactory = $requestFactory;
        $this->itemFactory = $itemFactory;
        $this->getCustomer = $getCustomer;
        $this->order = $order;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {

        if (!$this->helperData->isEnabled()) {
            throw new GraphQlNoSuchEntityException(__('RMA is disabled.'));
        }

        if ($this->helperData->versionCompare('2.3.3')) {
            if ($context->getExtensionAttributes()->getIsCustomer() === false) {
                throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
            }
        }
    }

    public function checkCustomer($orderIncrement, $context) {
        $customer = $this->getCustomer->execute($context);
        $order = $this->order->loadByIncrementId($orderIncrement);

        if ($customer->getId() !== $order->getCustomerId()) {
            throw new InputException(__('Something went wrong, please check the data again.'));
        }
    }
}
