<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Model;

use Magento\Framework\Api\SearchResults;
use Netresearch\AdminNotificationFeed\Api\Data\FeedSearchResultsInterface;

class FeedSearchResults extends SearchResults implements FeedSearchResultsInterface
{
}
