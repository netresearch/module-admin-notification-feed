<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\DataProvider;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Netresearch\AdminNotificationFeed\Api\Data\FeedInterface;
use Netresearch\AdminNotificationFeed\Model\Feed;
use Netresearch\AdminNotificationFeed\Model\ResourceModel\CollectionFactory;

class FeedProvider extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * RecipientStreet constructor.
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param mixed[] $meta
     * @param mixed[] $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    #[\Override]
    public function getData(): array
    {
        $loadedData = [];
        $data = parent::getData();

        if (empty($data['items'])) {
            return $data;
        }

        foreach ($data['items'] as $feed) {
            if (is_array($feed)) {
                $loadedData[$feed[FeedInterface::ENTITY_ID]] = $feed;
            }
        }

        return $loadedData;
    }

    /**
     * @return AbstractCollection
     */
    #[\Override]
    public function getCollection(): AbstractCollection
    {
        if ($this->collection === null) {
            $this->collection = $this->collectionFactory->create();
        }

        return parent::getCollection();
    }
}
