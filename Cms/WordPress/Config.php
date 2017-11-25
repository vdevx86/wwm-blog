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

namespace Wwm\Blog\Cms\WordPress;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\UrlInterface;
use Wwm\Blog\Magento\Framework\App\Request\Http\Uri\ParserInterface;

class Config extends \Magento\Framework\App\Config implements ConfigInterface
{
    
    const ROUTER_NAME_DEFAULT = 'blog';
    const MAINMENU_TITLE_DEFAULT = 'Blog';
    
    protected $storeManager;
    
    protected $store = null;
    protected $installationPath = null;
    protected $routeName = null;
    protected $baseUrl = null;
    
    public function __construct(
        \Magento\Framework\App\Config\ScopeCodeResolver $scopeCodeResolver,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $types = []
    ) {
        parent::__construct($scopeCodeResolver, $types);
        $this->storeManager = $storeManager;
    }
    
    public function getStore()
    {
        if ($this->store === null) {
            $this->store = $this->storeManager->getStore();
        }
        return $this->store;
    }
    
    public function getValue(
        $path,
        $scope = ScopeInterface::SCOPE_STORE,
        $storeId = false
    ) {
        if (!$storeId) {
            $storeId = $this->getStore()->getId();
        }
        return parent::getValue(self::PREFIX . $path, $scope, $storeId);
    }
    
    public function isModuleEnabled()
    {
        return $this->getValue('general/status');
    }
    
    public function getInstallationPath()
    {
        if ($this->installationPath === null) {
            $this->installationPath = $this->getValue('general/path');
        }
        return $this->installationPath;
    }
    
    public function getRouteName()
    {
        if ($this->routeName === null) {
            $routeName = $this->getValue('general/route');
            if (!$routeName) {
                $routeName = static::ROUTER_NAME_DEFAULT;
            }
            $this->routeName = $routeName;
        }
        return $this->routeName;
    }
    
    public function getBaseUrlFrontend()
    {
        return $this->getStore()->getBaseUrl(UrlInterface::URL_TYPE_LINK);
    }
    
    public function getBaseUrl()
    {
        if ($this->baseUrl === null) {
            $this->baseUrl = $this->getBaseUrlFrontend() .
                $this->getRouteName() .
                ParserInterface::DELIMITER;
        }
        return $this->baseUrl;
    }
    
    public function isMainMenuAdd()
    {
        return $this->getValue('mainmenu/add');
    }
    
    public function getMainMenuTitle()
    {
        $title = $this->getValue('mainmenu/title');
        if (!$title) {
            $title = __(static::MAINMENU_TITLE_DEFAULT);
        }
        return $title;
    }
    
}
