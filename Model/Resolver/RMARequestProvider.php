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
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Mageplaza\RMA\Api\SearchResult\RequestSearchResultInterface;

/**
 * Class RMARequestProvider
 * @package Mageplaza\RMAGraphQl\Model\Resolver
 */
class RMARequestProvider extends AbstractRMARequest
{
    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        parent::resolve($field, $context, $info, $value, $args);

        $this->validateArgs($args);
        $searchCriteria = $this->searchCriteria->build('mp_rma', $args);
        $searchCriteria->setCurrentPage($args['currentPage']);
        $searchCriteria->setPageSize($args['pageSize']);
        $searchResult = $this->getSearchResult($context->getUserId(), $searchCriteria);

        return [
            'total_count' => $searchResult->getTotalCount(),
            'items'       => $searchResult->getItems(),
            'page_info'   => $this->getPageInfo($searchResult, $args)
        ];
    }

    /**
     * @param SearchCriteriaInterface $searchResult
     * @param array $args
     *
     * @return array
     * @throws GraphQlInputException
     */
    private function getPageInfo($searchResult, $args)
    {
        $totalPages  = ceil($searchResult->getTotalCount() / $args['pageSize']);
        $currentPage = $args['currentPage'];

        if ($currentPage > $totalPages && $searchResult->getTotalCount() > 0) {
            throw new GraphQlInputException(
                __(
                    'currentPage value %1 specified is greater than the %2 page(s) available.',
                    [$currentPage, $totalPages]
                )
            );
        }

        return [
            'pageSize'        => $args['pageSize'],
            'currentPage'     => $currentPage,
            'hasNextPage'     => $currentPage < $totalPages,
            'hasPreviousPage' => $currentPage > 1,
            'startPage'       => 1,
            'endPage'         => $totalPages,
        ];
    }

    /**
     * @param int $customerId
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return RequestSearchResultInterface|mixed
     */
    public function getSearchResult($customerId, $searchCriteria)
    {
        $searchResult = $this->requestManagement->getMine($customerId, $searchCriteria);

        foreach ($searchResult->getItems() as $item) {
            $item->setRequestItem($item->getRequestItem());
            $item->setRequestReply($item->getRequestReply());
            $item->setRequestShippingLabel($item->getRequestShippingLabel());
        }

        return $searchResult;
    }

    /**
     * @param array $args
     *
     * @throws GraphQlInputException
     */
    private function validateArgs(array $args): void
    {
        if (isset($args['currentPage']) && $args['currentPage'] < 1) {
            throw new GraphQlInputException(__('currentPage value must be greater than 0.'));
        }

        if (isset($args['pageSize']) && $args['pageSize'] < 1) {
            throw new GraphQlInputException(__('pageSize value must be greater than 0.'));
        }
    }
}
