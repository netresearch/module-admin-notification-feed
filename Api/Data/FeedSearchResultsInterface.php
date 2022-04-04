<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * @api
 */
interface FeedSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get feed list.
     *
     * @return FeedInterface[]
     */
    public function getItems();

    /**
     * Set feed list.
     *
     * @param FeedInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
