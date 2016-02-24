<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mehulchaudhari\Daisyconfeed\Controller\Adminhtml\Daisyconfeed;


class Generate extends \Mehulchaudhari\Daisyconfeed\Controller\Adminhtml\Daisyconfeed
{
    /**
     * Generate daisyconfeed
     *
     * @return void
     */
    public function execute()
    {
        // init and load daisyconfeed model
        $id = $this->getRequest()->getParam('daisyconfeed_id');
        $daisyconfeed = $this->_objectManager->create('Mehulchaudhari\Daisyconfeed\Model\Daisyconfeed');
        /* @var $daisyconfeed \Magento\Daisyconfeed\Model\Daisyconfeed */
        $daisyconfeed->load($id);
        // if daisyconfeed record exists
        if ($daisyconfeed->getId()) {
            try {
                $daisyconfeed->generateXml();

                $this->messageManager->addSuccess(
                    __('The daisyconfeed "%1" has been generated.', $daisyconfeed->getDaisyconfeedFilename())
                );
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('We can\'t generate the daisyconfeed right now.'));
            }
        } else {
            $this->messageManager->addError(__('We can\'t find a daisyconfeed to generate.'));
        }

        // go to grid
        $this->_redirect('adminhtml/*/');
    }
}
