<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Model;

use Magento\Framework\Model\AbstractModel;
use Netresearch\AdminNotificationFeed\Api\Data\FeedInterface;
use Netresearch\AdminNotificationFeed\Model\ResourceModel\Feed as FeedResource;

class Feed extends AbstractModel implements FeedInterface
{
    /**
     * Initialize Feed resource model.
     */
    protected function _construct()
    {
        $this->_init(FeedResource::class);
        parent::_construct();
    }

    public function getEntityId(): ?int
    {
        $entityId = $this->getData(self::ENTITY_ID);
        return $entityId ? (int) $entityId : null;
    }

    public function isEnabled(): bool
    {
        return (bool) $this->getData(self::IS_ENABLED);
    }

    public function getFeedTitle(): string
    {
        return $this->getData(self::FEED_TITLE);
    }

    public function getFeedUrl(): string
    {
        return $this->getData(self::FEED_URL);
    }

    public function getSeverity(): int
    {
        return (int) $this->getData(self::SEVERITY);
    }

    public function getUpdatedAt(): string
    {
        return $this->getData(self::UPDATED_AT);
    }
}
