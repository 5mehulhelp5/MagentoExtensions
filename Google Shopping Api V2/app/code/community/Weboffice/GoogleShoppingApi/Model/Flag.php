<?php
/**
 * @category	Weboffice
 * @package     Weboffice_GoogleShoppingApi
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @copyright   Copyright (c) 2015 Weboffice UG (haftungsbeschränkt) (http://www.Weboffice.de)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Google shopping synchronization operations flag
 *
 * @category	Weboffice
 * @package     Weboffice_GoogleShoppingApi
 * @author      Magento Core Team <core@magentocommerce.com>
 * @author      Weboffice UG (haftungsbeschränkt) <magedev@Weboffice.eu>
 */
class Weboffice_GoogleShoppingApi_Model_Flag extends Mage_Core_Model_Flag
{
    /**
     * Flag time to live in seconds
     */
    const FLAG_TTL = 72000;

    /**
     * Synchronize flag code
     *
     * @var string
     */
    protected $_flagCode = 'googleshoppingapi';

    /**
     * Lock flag
     */
    public function lock()
    {
        $this->setState(1)
            ->save();
    }

    /**
     * Check wheter flag is locked
     *
     * @return bool
     */
    public function isLocked()
    {
        return !!$this->getState() && !$this->isExpired();
    }

    /**
     * Unlock flag
     */
    public function unlock()
    {
        $lastUpdate = $this->getLastUpdate();
        $this->loadSelf();
        $this->setState(0);
        if ($lastUpdate == $this->getLastUpdate()) {
            $this->save();
        }
    }

    /**
     * Check whether flag is unlocked by expiration
     *
     * @return bool
     */
    public function isExpired()
    {
        if (!!$this->getState() && Weboffice_GoogleShoppingApi_Model_Flag::FLAG_TTL) {
            if ($this->getLastUpdate()) {
                return (time() > (strtotime($this->getLastUpdate()) + Weboffice_GoogleShoppingApi_Model_Flag::FLAG_TTL));
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}
