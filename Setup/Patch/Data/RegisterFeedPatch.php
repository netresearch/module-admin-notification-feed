<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Setup\Patch\Data;

use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Notification\MessageInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Netresearch\AdminNotificationFeed\Api\Data\FeedInterface;
use Netresearch\AdminNotificationFeed\Api\Data\FeedInterfaceFactory;
use Netresearch\AdminNotificationFeed\Model\FeedRepository;
use Psr\Log\LoggerInterface;

class RegisterFeedPatch implements DataPatchInterface, PatchRevertableInterface
{
    private const FEED_URL = 'https://feed.dhl.netresearch.de/de/blog.atom';

    /**
     * @var FeedInterfaceFactory
     */
    private $feedFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FeedRepository
     */
    private $feedRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        FeedInterfaceFactory $feedFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FeedRepository $feedRepository,
        LoggerInterface $logger
    ) {
        $this->feedFactory = $feedFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->feedRepository = $feedRepository;
        $this->logger = $logger;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    /**
     * Register default DHL feed.
     *
     * @return void
     */
    public function apply(): void
    {
        $feed = $this->feedFactory->create();
        $feed->setData([
            FeedInterface::IS_ENABLED => true,
            FeedInterface::FEED_TITLE => 'DHL & Magento',
            FeedInterface::FEED_URL => self::FEED_URL,
            FeedInterface::SEVERITY => MessageInterface::SEVERITY_NOTICE,
        ]);

        try {
            $this->feedRepository->save($feed);
        } catch (CouldNotSaveException $exception) {
            $this->logger->error('Unable to save feed.', ['exception' => $exception]);
        }
    }

    public function revert(): void
    {
        $feeds = $this->feedRepository->getList($this->searchCriteriaBuilder->create());
        array_walk(
            $feeds,
            function (FeedInterface $feed) {
                try {
                    $this->feedRepository->delete($feed);
                } catch (CouldNotDeleteException $exception) {
                    $this->logger->error('Unable to delete feed.', ['exception' => $exception]);
                }
            }
        );
    }
}
