<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Block\Adminhtml\Edit\Buttons;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Netresearch\AdminNotificationFeed\Model\FeedRepository;

class DeleteButton implements ButtonProviderInterface
{
    /**
     * @var RequestInterface
     */
    private $request;


    /**
     * @var FeedRepository
     */
    private $feedRepository;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    public function __construct(RequestInterface $request, FeedRepository $feedRepository, UrlInterface $urlBuilder)
    {
        $this->request = $request;
        $this->feedRepository = $feedRepository;
        $this->urlBuilder = $urlBuilder;
    }

    public function getButtonData(): array
    {
        $feedId = (int) $this->request->getParam('feed_id');
        if (!$feedId) {
            return [];
        }

        try {
            $feed = $this->feedRepository->get((int) $this->request->getParam('feed_id'));
        } catch (NoSuchEntityException $exception) {
            return [];
        }

        $confMessage = __('Are you sure you want to delete feed "%1"?', $feed->getFeedTitle());
        $deleteUrl = $this->urlBuilder->getUrl('*/*/delete', ['feed_id' => $feed->getEntityId()]);
        $data = '{"data": {}}';

        return [
            'label' => __('Delete'),
            'class' => 'delete',
            'on_click' => "deleteConfirm('$confMessage', '$deleteUrl', $data)",
            'sort_order' => 20,
        ];
    }
}
