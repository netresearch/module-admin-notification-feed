<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Model\ResourceModel\Grid;

use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\View\Element\UiComponent\DataProvider\Document;
use Netresearch\AdminNotificationFeed\Model\ResourceModel\Collection as FeedCollection;
use Psr\Log\LoggerInterface;

class Collection extends FeedCollection implements SearchResultInterface
{
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        ?AdapterInterface $connection = null,
        ?AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $connection, $resource);

        $this->setModel(Document::class);
    }
    /**
     * @var AggregationInterface
     */
    private $aggregations;

    #[\Override]
    public function setItems(?array $items = null)
    {
        return $this;
    }

    #[\Override]
    public function getAggregations()
    {
        return $this->aggregations;
    }

    #[\Override]
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
        return $this;
    }

    #[\Override]
    public function getSearchCriteria()
    {
        return null;
    }

    #[\Override]
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria)
    {
        return $this;
    }

    #[\Override]
    public function getTotalCount()
    {
        return $this->getSize();
    }

    #[\Override]
    public function setTotalCount($totalCount)
    {
        return $this;
    }
}
