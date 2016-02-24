<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mehulchaudhari\Daisyconfeed\Controller\Adminhtml\Daisyconfeed;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;

class Delete extends \Mehulchaudhari\Daisyconfeed\Controller\Adminhtml\Daisyconfeed
{
    /**
     * Delete action
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Framework\Filesystem\Directory\Write $directory */
        $directory = $this->_objectManager->get(
            'Magento\Framework\Filesystem'
        )->getDirectoryWrite(
            DirectoryList::ROOT
        );

        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('daisyconfeed_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create('Mehulchaudhari\Daisyconfeed\Model\Daisyconfeed');
                $model->setId($id);
                
                $model->load($id);
                // delete file
                $path = $directory->getRelativePath($model->getPreparedFilename());
                if ($model->getDaisyconfeedFilename() && $directory->isFile($path)) {
                    $directory->delete($path);
                }
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('You deleted the daisyconfeed.'));
                // go to grid
                $this->_redirect('adminhtml/*/');
                return;
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('adminhtml/*/edit', ['daisyconfeed_id' => $id]);
                return;
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a daisyconfeed to delete.'));
        // go to grid
        $this->_redirect('adminhtml/*/');
    }
}
