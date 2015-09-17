<?php

class Devsters_Gift_Adminhtml_GiftcardsController extends Mage_Adminhtml_Controller_Action
{
 
   public function indexAction() {
    $this->getLayout()->createBlock('gift/adminhtml_giftcards');
    $this->loadLayout();
    $this->renderLayout();
}

    public function exportCsvAction()
    {
        $fileName   = 'devstersgiftcards.csv';
        $content    = $this->getLayout()->createBlock('gift/adminhtml_giftcards_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'devstersgiftcards.xml';
        $content    = $this->getLayout()->createBlock('gift/adminhtml_giftcards_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }
    
    public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('gift/gift');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/index', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}
    
    
     public function massDeleteAction() {
        $giftcardIds = $this->getRequest()->getParam('gift');
        if(!is_array($giftcardIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($giftcardIds as $giftcardId ) {
                    $giftcard = Mage::getModel('gift/gift')->load($giftcardId);
                    $giftcard->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($giftcardIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
   
  public function updateBalanceAction()
   {
   try { 
    	  $fieldId = (int) $this->getRequest()->getParam('id');
   	  $giftCardBalance = $this->getRequest()->getParam('gift_card_balance');
   	  if ($fieldId) {
       	 	$model = Mage::getModel('gift/gift')->load($fieldId);
       	 	$model->setGiftCardBalance(number_format($giftCardBalance,2));
       	 	$model->save();
       	 	$successlog = 'Balance of '.$giftCardBalance. ' was successfully Updated for gift card id: '. $fieldId;
       	 	Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__($successlog)
                );
          }      
    	 } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
         }
      $this->_redirect('*/*/index');   
   }
   
    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream') {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK', '');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}