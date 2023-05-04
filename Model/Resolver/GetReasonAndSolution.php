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
use Mageplaza\RMA\Helper\Data;

/**
 * Class ConfigData
 * @package Mageplaza\RMAGraphQl\Model\Resolver
 */
class GetReasonAndSolution extends AbstractRMARequest
{
    /**
     * @param Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     *
     * @return array
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        return [
            'enabled'   => $this->helperData->isEnabled(),
            'location'  => $this->helperData->getConfigGeneral('location'),
            'policy'    => [
                    'identifier' => $this->helperData->getConfigGeneral('policy'),
                    'policy_location' => $this->helperData->getConfigGeneral('policy_location')
                ],
            'each_item' => $this->helperData->getModuleConfig('request/each_item'),
            'allow_attachment'  => $this->helperData->getModuleConfig('request/allow_attachment'),
            'file_extensions'   => $this->helperData->getModuleConfig('request/file_extensions'),
            'reason'    => $this->getData('rma/reason'),
            'solution'  => $this->getData('rma/solution'),
            'additional_field' => $this->getDataAdditional()
        ];
    }

    /**
     * @param $config
     *
     * @return array
     */
    public function getData($config)
    {
        $items = Data::jsonDecode($this->helperData->getRequestConfig($config));
        $options = [];
        if (count($items)) {
            foreach ($items['name'] as $value => $content) {
                $options[] =    [
                    'value' => $value,
                    'content' => $content
                ];
            }
        }
        return $options;
    }

    /**
     * @return array
     */
    public function getDataAdditional()
    {
        $items = Data::jsonDecode($this->helperData->getRequestConfig('rma/additional_field'));
        $options = [];
        if (count($items)) {
            foreach ($items['name'] as $value => $content) {
                $options[] =    [
                    'value'     => $value,
                    'content'   => $content['title'],
                    'type'      => $content['type'],
                    'is_require'=> $content['is_require'],
                    'sort'      => $content['sort'],
                    'validation'=> $content['validation']
                ];
            }
        }
        return $options;
    }
}
