<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Netresearch\AdminNotificationFeed\Api\Data\FeedInterface;
use Netresearch\AdminNotificationFeed\Api\Data\FeedInterfaceFactory;
use Netresearch\AdminNotificationFeed\Api\Data\FeedSearchResultsInterface;
use Netresearch\AdminNotificationFeed\Api\Data\FeedSearchResultsInterfaceFactory;
use Netresearch\AdminNotificationFeed\Model\ResourceModel\Feed as FeedResource;
use Netresearch\AdminNotificationFeed\Model\ResourceModel\CollectionFactory;

class FeedRepository
{
    /**
     * @var FeedInterfaceFactory
     */
    private $feedFactory;

    /**
     * @var FeedResource
     */
    private $feedResource;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var FeedSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    public function __construct(
        FeedInterfaceFactory $feedFactory,
        FeedResource $feedResource,
        CollectionFactory $collectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        FeedSearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->feedFactory = $feedFactory;
        $this->feedResource = $feedResource;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @param int $feedId
     * @return FeedInterface
     * @throws NoSuchEntityException
     */
    public function get(int $feedId): FeedInterface
    {
        $feed = $this->feedFactory->create();

        try {
            $this->feedResource->load($feed, $feedId);
        } catch (\Exception) {
            throw new NoSuchEntityException(__('Unable to load feed with id %1.', $feedId));
        }

        if (!$feed->getId()) {
            throw new NoSuchEntityException(__('Unable to load feed with id %1.', $feedId));
        }

        return $feed;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): FeedSearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @param FeedInterface|Feed $feed
     * @return FeedInterface|Feed
     * @throws CouldNotSaveException
     */
    public function save(FeedInterface $feed): FeedInterface
    {
        try {
            $this->feedResource->save($feed);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__('Unable to save feed: %1.', $exception->getMessage()), $exception);
        }

        return $feed;
    }

    /**
     * @param FeedInterface|Feed $feed
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(FeedInterface $feed): bool
    {
        try {
            $this->feedResource->delete($feed);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Unable to delete feed: %1', $exception->getMessage()), $exception);
        }

        return true;
    }
}
