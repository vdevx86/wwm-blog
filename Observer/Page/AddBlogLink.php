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

namespace Wwm\Blog\Observer\Page;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Data\Tree\Node;
use Wwm\Blog\Helper\Config;

class AddBlogLink implements ObserverInterface
{
    
    protected $config;
    
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }
    
    public function getConfig()
    {
        return $this->config;
    }
    
    public function execute(Observer $observer)
    {
        
        $config = $this->getConfig();
        if ($config->isMainMenuAdd()) {
            
            $menu = $observer->getMenu();
            $menu->addChild(new Node([
                'name' => __($config->getMainMenuTitle()),
                'id' => Config::XML_PATH_MAINMENU_ADD,
                'url' => $config->getBaseUrl(),
                'has_active' => false,
                'is_active' => false
            ], 'id', $menu->getTree(), $menu));
            
        }
        
        return $this;
        
    }
    
}
