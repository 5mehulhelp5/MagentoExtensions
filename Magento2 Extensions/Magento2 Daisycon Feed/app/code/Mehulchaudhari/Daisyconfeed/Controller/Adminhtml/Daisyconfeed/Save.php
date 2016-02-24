<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mehulchaudhari\Daisyconfeed\Controller\Adminhtml\Daisyconfeed;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller;

class Save extends \Mehulchaudhari\Daisyconfeed\Controller\Adminhtml\Daisyconfeed
{
    /**
     * Validate path for generation
     *
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    protected function validatePath(array $data)
    {
        if (!empty($data['daisyconfeed_filename']) && !empty($data['daisyconfeed_path'])) {
            $data['daisyconfeed_path'] = '/' . ltrim($data['daisyconfeed_path'], '/');
            $path = rtrim($data['daisyconfeed_path'], '\\/') . '/' . $data['daisyconfeed_filename'];
            /** @var $validator \Magento\MediaStorage\Model\File\Validator\AvailablePath */
            $validator = $this->_objectManager->create('Magento\MediaStorage\Model\File\Validator\AvailablePath');
            /** @var $helper \Mehulchaudhari\Daisyconfeed\Helper\Data */
            $helper = $this->_objectManager->get('Mehulchaudhari\Daisyconfeed\Helper\Data');
            $validator->setPaths($helper->getValidPaths());
            if (!$validator->isValid($path)) {
                foreach ($validator->getMessages() as $message) {
                    $this->messageManager->addError($message);
                }
                // save data in session
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
                // redirect to edit form
                return false;
            }
        }
        return true;
    }

    /**
     * Clear daisyconfeed
     *
     * @param \Mehulchaudhari\Daisyconfeed\Model\Daisyconfeed $model
     * @return void
     */
    protected function clearSiteMap(\Mehulchaudhari\Daisyconfeed\Model\Daisyconfeed $model)
    {
        /** @var \Magento\Framework\Filesystem\Directory\Write $directory */
        $directory = $this->_objectManager->get('Magento\Framework\Filesystem')
            ->getDirectoryWrite(DirectoryList::ROOT);

        if ($this->getRequest()->getParam('daisyconfeed_id')) {
            $model->load($this->getRequest()->getParam('daisyconfeed_id'));
            $fileName = $model->getDaisyconfeedFilename();

            $path = $model->getDaisyconfeedPath() . '/' . $fileName;
            if ($fileName && $directory->isFile($path)) {
                $directory->delete($path);
            }
        }
    }

    /**
     * Save data
     *
     * @param array $data
     * @return string|bool
     */
    protected function saveData($data)
    {
        // init model and set data
        /** @var \Mehulchaudhari\Daisyconfeed\Model\Daisyconfeed $model */
        $model = $this->_objectManager->create('Mehulchaudhari\Daisyconfeed\Model\Daisyconfeed');
        $this->clearSiteMap($model);
        $model->setData($data);

        // try to save it
        try {
            // save the data
            $model->save();
            // display success message
            $this->messageManager->addSuccess(__('You saved the daisyconfeed.'));
            // clear previously saved data from session
            $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
            return $model->getId();
        } catch (\Exception $e) {
            // display error message
            $this->messageManager->addError($e->getMessage());
            // save data in session
            $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
        }
        return false;
    }

    /**
     * Get result after saving data
     *
     * @param string|bool $id
     * @return \Magento\Framework\Controller\ResultInterface
     */
    protected function getResult($id)
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(Controller\ResultFactory::TYPE_REDIRECT);
        if ($id) {
            // check if 'Save and Continue'
            if ($this->getRequest()->getParam('back')) {
                $resultRedirect->setPath('adminhtml/*/edit', ['daisyconfeed_id' => $id]);
                return $resultRedirect;
            }
            // go to grid or forward to generate action
            if ($this->getRequest()->getParam('generate')) {
                $this->getRequest()->setParam('daisyconfeed_id', $id);
                return $this->resultFactory->create(Controller\ResultFactory::TYPE_FORWARD)
                    ->forward('generate');
            }
            $resultRedirect->setPath('adminhtml/*/');
            return $resultRedirect;
        }
        $resultRedirect->setPath(
            'adminhtml/*/edit',
            ['daisyconfeed_id' => $this->getRequest()->getParam('daisyconfeed_id')]
        );
        return $resultRedirect;
    }

    /**
     * Save action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if data sent
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(Controller\ResultFactory::TYPE_REDIRECT);
        if ($data) {
            if (!$this->validatePath($data)) {
                $resultRedirect->setPath(
                    'adminhtml/*/edit',
                    ['daisyconfeed_id' => $this->getRequest()->getParam('daisyconfeed_id')]
                );
                return $resultRedirect;
            }
            return $this->getResult($this->saveData($data));
        }
        $resultRedirect->setPath('adminhtml/*/');
        return $resultRedirect;
    }
}
