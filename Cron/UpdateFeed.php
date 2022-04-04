<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Cron;

use Laminas\Feed\Reader\Collection\Category;
use Laminas\Feed\Reader\Reader;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Notification\MessageInterface;
use Magento\Framework\Notification\NotifierInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Netresearch\AdminNotificationFeed\Api\Data\FeedInterface;
use Netresearch\AdminNotificationFeed\Model\FeedRepository;
use Psr\Log\LoggerInterface;

class UpdateFeed
{
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FeedRepository
     */
    private $feedRepository;

    /**
     * @var NotifierInterface
     */
    private $notifier;

    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FeedRepository $feedRepository,
        NotifierInterface $notifier,
        FilterManager $filterManager,
        Escaper $escaper,
        DateTime $date,
        LoggerInterface $logger
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->feedRepository = $feedRepository;
        $this->notifier = $notifier;
        $this->filterManager = $filterManager;
        $this->escaper = $escaper;
        $this->date = $date;
        $this->logger = $logger;
    }

    private function getSeverityFromCategories(Category $categoryCollection, int $defaultSeverity): int
    {
        foreach ($categoryCollection->getValues() as $category) {
            switch ($category) {
                case 'CRITICAL':
                    return MessageInterface::SEVERITY_CRITICAL;
                case 'MAJOR':
                    return MessageInterface::SEVERITY_MAJOR;
                case 'MINOR':
                    return MessageInterface::SEVERITY_MINOR;
                case 'NOTICE':
                    return MessageInterface::SEVERITY_NOTICE;
            }
        }

        return $defaultSeverity;
    }

    public function execute()
    {
        $filter = $this->filterBuilder
            ->setField(FeedInterface::IS_ENABLED)
            ->setValue(true)
            ->setConditionType('eq')
            ->create();
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter($filter)
            ->create();

        foreach ($this->feedRepository->getList($searchCriteria)->getItems() as $subscription) {
            try {
                $lastUpdated = new \DateTimeImmutable($subscription->getUpdatedAt());
                $feed = Reader::import($subscription->getFeedUrl());

                if ($feed->getDateModified() > $lastUpdated) {
                    foreach ($feed as $entry) {
                        if ($entry->getDateModified() > $lastUpdated) {
                            $content = $this->filterManager->removeTags($entry->getContent());
                            $this->notifier->add(
                                $this->getSeverityFromCategories($entry->getCategories(), $subscription->getSeverity()),
                                $this->escaper->escapeHtml($entry->getTitle()),
                                $this->escaper->escapeHtml($content),
                                $this->escaper->escapeHtml($entry->getLink(1) ?? $entry->getLink()),
                                false
                            );
                        }
                    }
                }

                $subscription->setData(FeedInterface::UPDATED_AT, $this->date->gmtDate());
                $this->feedRepository->save($subscription);
            } catch (\RuntimeException $exception) {
                $this->logger->error(
                    'Error reading from feed URI ' . $subscription->getFeedUrl(),
                    ['exception' => $exception]
                );
            } catch (LocalizedException $exception) {
                $this->logger->error($exception->getLogMessage());
            }
        }
    }
}
