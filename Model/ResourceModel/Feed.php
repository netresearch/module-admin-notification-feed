<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Netresearch\AdminNotificationFeed\Api\Data\FeedInterface;

class Feed extends AbstractDb
{
    /**
     * Init main table and primary key.
     *
     * @return void
     */
    #[\Override]
    protected function _construct()
    {
        $this->_init('nr_admin_notification_feed', FeedInterface::ENTITY_ID);
    }

    #[\Override]
    protected function _beforeSave(AbstractModel $object): self
    {
        $wasEnabled = $object->getOrigData(FeedInterface::IS_ENABLED);
        $isEnabled = $object->getData(FeedInterface::IS_ENABLED);

        if (!$wasEnabled && $isEnabled) {
            // when (re-)activating a feed, make sure to reset last modification date.
            $object->setData(FeedInterface::UPDATED_AT, new \Zend_Db_Expr('CURRENT_TIMESTAMP()'));
        }

        return parent::_beforeSave($object);
    }
}
