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
 * @package     Mageplaza_StoreLocatorGraphQl
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

declare(strict_types=1);

namespace Mageplaza\RMAGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\RMA\Helper\Data;

/**
 * Class ConfigData
 * @package Mageplaza\RMAGraphQl\Model\Resolver
 */
class GetReasonAndSolution extends AbstractRMARequest
{
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $data[] = [
            'reason' => $this->getData('rma/reason'),
            'solution' => $this->getData('rma/solution'),
            'additional_field' => $this->getData('rma/additional_field')
        ];

        return Data::jsonEncode($data);
    }

    public function getData($config)
    {
        $items = Data::jsonDecode($this->helperData->getRequestConfig($config));
        $options = [];

        if (count($items)) {
            foreach ($items['name'] as $value => $content) {
                $options[] = compact('value', 'content');
            }
        }

        return $options;
    }
}
