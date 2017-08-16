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

class Config
{
    
    const XML_PATH_STATUS = 'wwm_blog/general/status';
    const XML_PATH_INSTALLATION_PATH = 'wwm_blog/general/path';
    const XML_PATH_ROUTE_NAME = 'wwm_blog/general/route';
    
    const ROUTER_NAME_DEFAULT = 'blog';
    
    const XML_PATH_MAINMENU_ADD = 'wwm_blog/mainmenu/add';
    const XML_PATH_MAINMENU_TITLE = 'wwm_blog/mainmenu/title';
    
    const MAINMENU_TITLE_DEFAULT = 'Blog';
    
    protected $scopeConfig;
    protected $storeManager;
    
    protected $installationPath = null;
    protected $routeName = null;
    protected $baseUrl = null;
    
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManager $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }
    
    public function isModuleEnabled()
    {
        return $this->scopeConfig->getValue(static::XML_PATH_STATUS);
    }
    
    public function getInstallationPath()
    {
        if ($this->installationPath === null) {
            $this->installationPath = $this->scopeConfig->getValue(static::XML_PATH_INSTALLATION_PATH);
        }
        return $this->installationPath;
    }
    
    public function getRouteName()
    {
        if ($this->routeName === null) {
            $routeName = $this->scopeConfig->getValue(static::XML_PATH_ROUTE_NAME);
            if (!$routeName) {
                $routeName = static::ROUTER_NAME_DEFAULT;
            }
            $this->routeName = $routeName;
        }
        return $this->routeName;
    }
    
    public function getBaseUrlFrontend()
    {
        return $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_LINK);
    }
    
    public function getBaseUrl()
    {
        if ($this->baseUrl === null) {
            $this->baseUrl = $this->getBaseUrlFrontend() . $this->getRouteName() .
                \Wwm\Blog\Magento\Framework\App\Request\Http\Uri\ParserInterface::DELIMITER;
        }
        return $this->baseUrl;
    }
    
    public function isMainMenuAdd()
    {
        return $this->scopeConfig->getValue(
            static::XML_PATH_MAINMENU_ADD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    public function getMainMenuTitle()
    {
        $title = $this->scopeConfig->getValue(
            static::XML_PATH_MAINMENU_TITLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if (!$title) {
            $title = __(static::MAINMENU_TITLE_DEFAULT);
        }
        return $title;
    }
    
}
