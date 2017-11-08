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

use Magento\Framework\App\ObjectManager;
use Wwm\Blog\Cms\WordPress\ThemeInterface;
use Wwm\Blog\Cms\WordPress\LoadType;

final class ThemeFactory
{
    
    protected $objectManager;
    protected $config;
    protected $entryPoint;
    protected $loadType;
    protected $instanceName;
    
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Wwm\Blog\Helper\Config $config,
        EntryPoint $entryPoint,
        \Wwm\Blog\Cms\WordPress\LoadType $loadType,
        $instanceName = ThemeInterface::class
    ) {
        $this->objectManager = $objectManager;
        $this->config = $config;
        $this->entryPoint = $entryPoint;
        $this->loadType = $loadType;
        $this->instanceName = $instanceName;
    }
    
    public function create()
    {
        
        $result = null;
        $theme = $this->objectManager->get($this->instanceName);
        
        if ($theme->getInitState()) {
            $result = $theme;
        } else {
            
            $theme->setHomeURLOriginal($theme->homeUrl());
            $theme->setHomeURLNew(
                $this->config->getBaseUrlFrontend() .
                $this->config->getRouteName()
            );
            
            $theme->removeHeadActions();
            $theme->enableThemeFeatures();
            $theme->loadThemeTextdomain(
                $theme->getOptions()->getTextDomain()
            );
            $theme->initImageSizes();
            
            if ($this->entryPoint->isAdmin()) {
                $theme->getHookStorageGroup()
                    ->getCommonAdmin()
                    ->create();
            } else {
                if ($this->loadType->isLogin()) {
                    $theme->getHookStorageGroup()
                        ->getScript()
                        ->create();
                } else {
                    $theme->getHookStorageGroup()
                        ->getCommon()
                        ->create();
                }
            }
            
            $theme->setInitState();
            $result = $theme;
            
        }
        
        return $result;
        
    }
    
    public static function getInstance()
    {
        return ObjectManager::getInstance()->get(self::class)->create();
    }
    
}
