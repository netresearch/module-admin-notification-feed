<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    private const ROUTE_PATH_EDIT = 'nradminfeed/feed/edit';
    private const ROUTE_PATH_DELETE = 'nradminfeed/feed/delete';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    #[\Override]
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$fieldName]['edit'] = [
                    'href' => $this->urlBuilder->getUrl(self::ROUTE_PATH_EDIT, ['feed_id' => $item['entity_id']]),
                    'label' => __('Edit'),
                    'hidden' => false,
                    '__disableTmpl' => true
                ];

                $item[$fieldName]['delete'] = [
                    'href' => $this->urlBuilder->getUrl(self::ROUTE_PATH_DELETE, ['feed_id' => $item['entity_id']]),
                    'label' => __('Delete'),
                    'hidden' => false,
                    '__disableTmpl' => true
                ];

            }
        }

        return parent::prepareDataSource($dataSource);
    }
}
