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

use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\RMA\Api\SearchResult\RuleSearchResultInterface;
use Mageplaza\RMA\Helper\Data;
use Mageplaza\RMA\Model\Api\RuleManagement;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;

/**
 * Class RMARequestProvider
 * @package Mageplaza\RMAGraphQl\Model\Resolver
 */
class RMARuleProvider implements ResolverInterface
{

    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @var RuleManagement
     */
    protected $ruleManagement;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteria;

    public function __construct(
        Data $helperData,
        RuleManagement $ruleManagement,
        SearchCriteriaBuilder $searchCriteria
    ) {
        $this->helperData           = $helperData;
        $this->ruleManagement       = $ruleManagement;
        $this->searchCriteria       = $searchCriteria;
    }
    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!$this->helperData->isEnabled()) {
            return [];
        }

        $rulesData = [];
        $searchCriteria = $this->searchCriteria->build('mp_rma_rules', $args);
        $searchResult = $this->getSearchResult($searchCriteria);

        if(count($searchResult->getItems())) {
            foreach ($searchResult->getItems() as $item)
            {
                $rulesData[] = $item->getData();
            }
        }
        return $rulesData;
    }

    /**
     * @param int $customerId
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return RuleSearchResultInterface|mixed
     */
    public function getSearchResult($searchCriteria)
    {
        $searchResult = $this->ruleManagement->getList($searchCriteria);
        return $searchResult;
    }
}
