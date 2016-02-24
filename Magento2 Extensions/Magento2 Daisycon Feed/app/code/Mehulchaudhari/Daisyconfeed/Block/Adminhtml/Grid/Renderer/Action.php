<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mehulchaudhari\Daisyconfeed\Block\Adminhtml\Grid\Renderer;

/**
 * Daisyconfeed grid action column renderer
 */
class Action extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Action
{
    /**
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $this->getColumn()->setActions(
            [
                [
                    'url' => $this->getUrl('adminhtml/daisyconfeed/generate', ['daisyconfeed_id' => $row->getDaisyconfeedId()]),
                    'caption' => __('Generate'),
                ],
            ]
        );
        return parent::render($row);
    }
}
