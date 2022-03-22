<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Observer;

use Laminas\Feed\Reader\Collection\Category;
use Laminas\Feed\Reader\Exception\RuntimeException;
use Laminas\Feed\Reader\Reader;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Config\ConfigOptionsListConstants;
use Magento\Framework\Escaper;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Notification\MessageInterface;
use Magento\Framework\Notification\NotifierInterface;
use Netresearch\AdminNotificationFeed\Model\Feed;
use Psr\Log\LoggerInterface;
use Safe\DateTimeImmutable;

class UpdateFeed implements ObserverInterface
{
    private const KEY_LAST_UPDATED = 'nradminfeed_updated_at';

    /**
     * @var AuthSession
     */
    private $authSession;

    /**
     * @var DeploymentConfig
     */
    private $deploymentConfig;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var Feed
     */
    private $feed;

    /**
     * @var NotifierInterface
     */
    private $notifier;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        AuthSession $authSession,
        DeploymentConfig $deploymentConfig,
        DataPersistorInterface $dataPersistor,
        Feed $feed,
        NotifierInterface $notifier,
        Escaper $escaper,
        LoggerInterface $logger
    ) {
        $this->authSession = $authSession;
        $this->deploymentConfig = $deploymentConfig;
        $this->dataPersistor = $dataPersistor;
        $this->feed = $feed;
        $this->notifier = $notifier;
        $this->escaper = $escaper;
        $this->logger = $logger;
    }

    private function getSeverityFromCategories(Category $categoryCollection)
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

        return MessageInterface::SEVERITY_NOTICE;
    }

    public function execute(Observer $observer)
    {
//        $this->dataPersistor->clear(self::KEY_LAST_UPDATED);

        if ($this->authSession->isLoggedIn()) {
            $lastUpdated = new DateTimeImmutable(
                $this->dataPersistor->get(self::KEY_LAST_UPDATED)
                    ?: $this->deploymentConfig->get(ConfigOptionsListConstants::CONFIG_PATH_INSTALL_DATE)
            );

            try {
                $feed = Reader::import($this->feed->getUrl());
            } catch (RuntimeException $exception) {
                $this->logger->error(
                    'Error reading from feed URI ' . $this->feed->getUrl(),
                    ['exception' => $exception]
                );

                return;
            }

            if ($feed->getDateModified() > $lastUpdated) {
                foreach ($feed as $entry) {
                    if ($entry->getDateModified() > $lastUpdated) {
                        $this->notifier->add(
                            $this->getSeverityFromCategories($entry->getCategories()),
                            $this->escaper->escapeHtml($entry->getTitle()),
                            $this->escaper->escapeHtml($entry->getContent()),
                            $this->escaper->escapeHtml($entry->getLink(1) ?? $entry->getLink()),
                            false
                        );
                    }
                }

                $this->dataPersistor->set(self::KEY_LAST_UPDATED, $feed->getDateModified()->format('Y-m-d H:i:s'));
            }
        }
    }
}
