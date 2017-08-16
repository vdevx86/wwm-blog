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

class AddBlogLink implements \Magento\Framework\Event\ObserverInterface
{
    
    const NODE_ID = 'wwm';
    
    protected $config;
    protected $nodeFactory;
    
    public function __construct(
        \Wwm\Blog\Helper\Config $config,
        \Magento\Framework\Data\Tree\NodeFactory $nodeFactory
    ) {
        $this->config = $config;
        $this->nodeFactory = $nodeFactory;
    }
    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        
        if (
                $this->config->isModuleEnabled()
            &&  $this->config->isMainMenuAdd()
        ) {
            
            $menu = $observer->getMenu();
            $node = $this->nodeFactory->create([
                'data' => [
                    'name' => __($this->config->getMainMenuTitle()),
                    'url' => $this->config->getBaseUrl(),
                    'has_active' => false,
                    'is_active' => false
                ],
                'idField' => static::NODE_ID,
                'tree' => $menu->getTree(),
                'parent' => $menu
            ]);
            
            $menu->addChild($node);
            
        }
        
        return $this;
        
    }
    
}
