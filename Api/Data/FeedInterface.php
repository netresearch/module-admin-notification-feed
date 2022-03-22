<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Api\Data;

interface FeedInterface
{
    public function isEnabled(): bool;

    public function getUrl(): string;

    public function getUpdatedAt(): string;
}
