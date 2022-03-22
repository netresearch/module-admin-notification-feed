<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Model;

use Netresearch\AdminNotificationFeed\Api\Data\FeedInterface;

class Feed implements FeedInterface
{
    public function isEnabled(): bool
    {
        return true;
    }

    public function getUrl(): string
    {
        return 'https://www.grav.nr-cas/de/blog.rss';
    }

    public function getUpdatedAt(): string
    {
        return '';
    }
}
