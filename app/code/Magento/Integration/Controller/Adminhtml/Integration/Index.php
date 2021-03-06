<?php
/**
 *
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */
namespace Magento\Integration\Controller\Adminhtml\Integration;

class Index extends \Magento\Integration\Controller\Adminhtml\Integration
{
    /**
     * Integrations grid.
     *
     * @return void
     */
    public function execute()
    {
        $unsecureIntegrationsCount = $this->_integrationCollection->addUnsecureUrlsFilter()->getSize();
        if ($unsecureIntegrationsCount > 0) {
            // @codingStandardsIgnoreStart
            $this->messageManager->addNotice(__('Warning! Integrations not using HTTPS are insecure and potentially expose private or personally identifiable information')
            // @codingStandardsIgnoreEnd
            );
        }

        $this->_view->loadLayout();
        $this->_setActiveMenu('Magento_Integration::system_integrations');
        $this->_addBreadcrumb(__('Integrations'), __('Integrations'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Integrations'));
        $this->_view->renderLayout();
    }
}
