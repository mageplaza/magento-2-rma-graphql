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
use Magento\Framework\GraphQl\ConfigInterface;
use Magento\Framework\GraphQl\Query\Resolver\Argument\FieldEntityAttributesInterface;
use Mageplaza\RMA\Helper\Data;

/**
 * Class RulesFilterArgument
 * @package Mageplaza\RMAGraphQl\Model\Resolver
 */
class RulesFilterArgument implements FieldEntityAttributesInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * RulesFilterArgument constructor.
     *
     * @param ConfigInterface $config
     * @param Data $helperData
     */
    public function __construct(ConfigInterface $config, Data $helperData)
    {
        $this->config     = $config;
        $this->helperData = $helperData;
    }

    /**
     * @return array
     */
    public function getEntityAttributes(): array
    {
        $fields = [];
        /** @var Field $field */
        foreach ($this->config->getConfigElement('MpRmaRulesFilterInput')->getFields() as $field) {
            $fieldName          = $field->getName();
            $fields[$fieldName] = $fieldName;
        }
        return $fields;
    }
}
