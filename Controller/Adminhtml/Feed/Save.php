<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Controller\Adminhtml\Feed;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Netresearch\AdminNotificationFeed\Api\Data\FeedInterface;
use Netresearch\AdminNotificationFeed\Api\Data\FeedInterfaceFactory;
use Netresearch\AdminNotificationFeed\Model\FeedRepository;

class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Netresearch_AdminNotificationFeed::edit';

    /**
     * @var FeedRepository
     */
    private $feedRepository;

    /**
     * @var FeedInterfaceFactory
     */
    private $feedFactory;

    public function __construct(Context $context, FeedRepository $feedRepository, FeedInterfaceFactory $feedFactory)
    {
        $this->feedRepository = $feedRepository;
        $this->feedFactory = $feedFactory;

        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/index');

        $feedId = (int) $this->getRequest()->getParam('entity_id');
        if ($feedId) {
            try {
                $feed = $this->feedRepository->get($feedId);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addErrorMessage(__('This feed no longer exists.'));
                return $resultRedirect;
            }
        } else {
            $feed = $this->feedFactory->create();
        }

        $feed->addData([
            FeedInterface::IS_ENABLED => (bool) $this->getRequest()->getParam('is_enabled'),
            FeedInterface::FEED_TITLE => (string) $this->getRequest()->getParam('feed_title'),
            FeedInterface::FEED_URL => (string) $this->getRequest()->getParam('feed_url'),
            FeedInterface::SEVERITY => (int) $this->getRequest()->getParam('severity'),
        ]);

        try {
            $this->feedRepository->save($feed);
            $this->messageManager->addSuccessMessage($feedId ? __('Feed was successfully updated.') : __('Feed was successfully added.'));
        } catch (CouldNotSaveException $exception) {
            $this->messageManager->addExceptionMessage($exception);
        }

        return $resultRedirect;
    }
}
