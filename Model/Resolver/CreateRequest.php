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

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\RMA\Model\Request;

/**
 * Class CreateRequest
 * @package Mageplaza\RMAGraphQl\Model\Resolver
 */
class CreateRequest extends AbstractRMARequest
{
    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        parent::resolve($field, $context, $info, $value, $args);
        $this->checkCustomer($args[Request::ORDER_INCREMENT_ID], $context);
        $items = [];

        foreach ($args[Request::REQUEST_ITEM] as $child) {
            $items[] = $this->itemFactory->create()->setData($child);
        }

        $request = $this->requestFactory->create()->setData($args);
        $request->setRequestItem($items);
        $result = $this->requestManagement->save($request);
        $result->setRequestItem($result->getRequestItem());
        $result->setRequestReply($result->getRequestReply());
        $result->setRequestShippingLabel($result->getRequestShippingLabel());

        return $result;
    }
}
