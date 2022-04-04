<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Netresearch\AdminNotificationFeed\Controller\Adminhtml\Feed;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Netresearch_AdminNotificationFeed::edit';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    public function __construct(Context $context, PageFactory $resultPageFactory)
    {
        $this->resultPageFactory = $resultPageFactory;

        parent::__construct($context);
    }

    public function execute(): ResultInterface
    {
        $title = $this->getRequest()->getParam('feed_id') ? __('Edit Feed') : __('New Feed');
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Netresearch_AdminNotificationFeed::feeds')
            ->addBreadcrumb(__('System'), __('System'))
            ->addBreadcrumb(__('Notification Feeds'), __('Notification Feeds'));

        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }
}
