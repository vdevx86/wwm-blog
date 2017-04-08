<?php
/**
 * Copyright © 2017 Walk with Magento
 * See COPYING.txt for license details
 */

/**
 * Free integration between Magento 2 and WordPress
 *
 * @author Ovakimyan Vazgen <vdevx86job@gmail.com>
 * @copyright 2017 Walk with Magento (http://wwm-integrations.in.ua)
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License ("OSL") v. 3.0
 * @copyright 2017 Ovakimyan Vazgen <vdevx86job@gmail.com>
 */

namespace Wwm\Blog\Helper;

use Magento\Store\Model\ScopeInterface;
use Wwm\Blog\Controller\Router;

class Config
{
    
    const XML_PATH_STATUS = 'wwm_blog/general/status';
    const XML_PATH_INSTALLATION_PATH = 'wwm_blog/general/path';
    const XML_PATH_ROUTE_NAME = 'wwm_blog/general/route';
    
    const XML_PATH_MAINMENU_ADD = 'wwm_blog/mainmenu/add';
    const XML_PATH_MAINMENU_TITLE = 'wwm_blog/mainmenu/title';
    
    const MAINMENU_TITLE_DEFAULT = 'Blog';
    
    protected $_scopeConfig;
    protected $_storeManager;
    
    protected $_installationPath = null;
    protected $_routeName = null;
    protected $_baseUrl = null;
    
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManager $storeManager
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
    }
    
    public function getScopeConfig()
    {
        return $this->_scopeConfig;
    }
    
    public function getStoreManager()
    {
        return $this->_storeManager;
    }
    
    public function isModuleEnabled()
    {
        return $this->getScopeConfig()->getValue(static::XML_PATH_STATUS);
    }
    
    public function getInstallationPath()
    {
        if ($this->_installationPath === null) {
            $this->_installationPath = $this->getScopeConfig()->getValue(static::XML_PATH_INSTALLATION_PATH);
        }
        return $this->_installationPath;
    }
    
    public function getRouteName()
    {
        if ($this->_routeName === null) {
            $routeName = $this->getScopeConfig()->getValue(static::XML_PATH_ROUTE_NAME);
            if (!$routeName) {
                $routeName = Router::ROUTER_NAME_DEFAULT;
            }
            $this->_routeName = $routeName;
        }
        return $this->_routeName;
    }
    
    public function getBaseUrlFrontend()
    {
        return $this->getStoreManager()
            ->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK);
    }
    
    public function getBaseUrl()
    {
        if ($this->_baseUrl === null) {
            $this->_baseUrl = $this->getBaseUrlFrontend() . $this->getRouteName() . Router::URI_DELIMITER;
        }
        return $this->_baseUrl;
    }
    
    public function isMainMenuAdd()
    {
        return $this->getScopeConfig()->getValue(static::XML_PATH_MAINMENU_ADD, ScopeInterface::SCOPE_STORE);
    }
    
    public function getMainMenuTitle()
    {
        $title = $this->getScopeConfig()->getValue(static::XML_PATH_MAINMENU_TITLE, ScopeInterface::SCOPE_STORE);
        if (!$title) {
            $title = static::MAINMENU_TITLE_DEFAULT;
        }
        return $title;
    }
    
}
