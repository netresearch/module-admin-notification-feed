<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Model\OptionSource;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Notification\MessageInterface;

class Severity implements OptionSourceInterface
{
    #[\Override]
    public function toOptionArray(): array
    {
        return [
            [
                'value' => MessageInterface::SEVERITY_NOTICE,
                'label' => __('Notice'),
            ],
            [
                'value' => MessageInterface::SEVERITY_MINOR,
                'label' => __('Minor'),
            ],
            [
                'value' => MessageInterface::SEVERITY_MAJOR,
                'label' => __('Major'),
            ],
            [
                'value' => MessageInterface::SEVERITY_CRITICAL,
                'label' => __('Critical'),
            ],
        ];
    }
}
