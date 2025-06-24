<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Controller\Adminhtml\Feed;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Netresearch\AdminNotificationFeed\Model\FeedRepository;

class Delete extends Action implements HttpGetActionInterface, HttpPostActionInterface
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

    public function __construct(Context $context, FeedRepository $feedRepository)
    {
        $this->feedRepository = $feedRepository;

        parent::__construct($context);
    }

    #[\Override]
    public function execute(): ResultInterface
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/index');

        $feedId = (int) $this->getRequest()->getParam('feed_id');
        if ($feedId) {
            try {
                $feed = $this->feedRepository->get($feedId);
                $this->feedRepository->delete($feed);
                $this->messageManager->addSuccessMessage(
                    __('Feed "%1" was successfully removed.', $feed->getFeedTitle()
                ));
            } catch (NoSuchEntityException) {
                $this->messageManager->addErrorMessage(__('This feed no longer exists.'));
            } catch (CouldNotDeleteException $exception) {
                $this->messageManager->addExceptionMessage($exception);
            }
        }

        return $resultRedirect;
    }
}
