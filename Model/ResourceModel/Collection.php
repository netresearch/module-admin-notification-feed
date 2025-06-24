<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Netresearch\AdminNotificationFeed\Model\Feed;
use Netresearch\AdminNotificationFeed\Model\ResourceModel\Feed as FeedResource;

/**
 * @method Feed[] getItems()
 */
class Collection extends AbstractCollection
{
    #[\Override]
    protected function _construct()
    {
        $this->_init(Feed::class, FeedResource::class);
    }
}
