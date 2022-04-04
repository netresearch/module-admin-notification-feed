<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Api\Data;

/**
 * @api
 */
interface FeedInterface
{
    public const ENTITY_ID = 'entity_id';
    public const IS_ENABLED = 'is_enabled';
    public const FEED_TITLE = 'feed_title';
    public const FEED_URL = 'feed_url';
    public const SEVERITY = 'severity';
    public const UPDATED_AT = 'updated_at';

    public function getEntityId(): ?int;

    public function isEnabled(): bool;

    public function getFeedTitle(): string;

    public function getFeedUrl(): string;

    public function getSeverity(): int;

    public function getUpdatedAt(): string;
}
