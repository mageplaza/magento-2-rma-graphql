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

use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Sales\Api\Data\OrderInterface;
use Mageplaza\RMA\Helper\Data;
use Mageplaza\RMA\Model\Api\RequestManagement;
use Mageplaza\RMA\Model\Config\Source\RMARequest\Orders;
use Mageplaza\RMA\Model\Request\ItemFactory;
use Mageplaza\RMA\Model\Request\ReplyFactory;
use Mageplaza\RMA\Model\RequestFactory;

/**
 * Class RMARequestProvider
 * @package Mageplaza\RMAGraphQl\Model\Resolver
 */
class GetOrdersList extends AbstractRMARequest
{
    /**
     * @var Orders
     */
    protected $_orderToArray;

    /**
     * @inheritdoc
     */
    public function __construct(
        Data $helperData,
        RequestManagement $requestManagement,
        ReplyFactory $replyFactory,
        RequestFactory $requestFactory,
        ItemFactory $itemFactory,
        GetCustomer $getCustomer,
        OrderInterface $order,
        SearchCriteriaBuilder $searchCriteria,
        Orders $orders
    ) {
        $this->_orderToArray    = $orders;
        parent::__construct(
            $helperData,
            $requestManagement,
            $replyFactory,
            $requestFactory,
            $itemFactory,
            $getCustomer,
            $order,
            $searchCriteria
        );
    }

    /**
     * @param Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     *
     * @return array
     * @throws \Exception
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        parent::resolve($field, $context, $info, $value, $args);
        $customerId = $context->getUserId();
        $orderIds = $this->_orderToArray->toAvailableOptionArray($customerId);
        $ordersData = [];
        if (count($orderIds)) {
            foreach ($orderIds as $orderId) {
                $order = $this->order->loadByIncrementId($orderId['order_increment']);
                $ordersData[] = [
                    'order_id'          => $order->getId(),
                    'order_increment'   => $order->getIncrementId(),
                    'label'             => '#' . $order->getIncrementId(),
                    'email'             => $order->getCustomerEmail(),
                    'bill_lastname'      => $order->getBillingAddress()->getLastname(),
                    'zip_code'          => $order->getBillingAddress()->getPostcode(),
                    'items'             => $this->getOrderItems($order)
                ];
            }
        }
        return $ordersData;
    }

    /**
     * @param $order
     *
     * @return array
     */
    public function getOrderItems($order)
    {
        $orderItems = $order->getItems();
        $productItem = [];
        foreach ($orderItems as $item) {
            $productItem[] = [
                'item_id'       => $item->getItemId(),
                'product_id'    => $item->getProductId(),
                'name'          => $item->getName(),
                'sku'           => $item->getSku(),
                'qty'           => $item->getQtyOrdered(),
                'mp_is_return'  => $this->helperData->canReturnProduct($item->getProductId(), $order) ? true : false,
                'mp_qty_rma'    => $item->getMpQtyRma()
            ];

        }
//        var_dump($item->getData());die();
        return $productItem;
    }
}
