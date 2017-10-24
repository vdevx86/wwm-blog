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

use Magento\Framework\Component\ComponentRegistrar as ComponentRegistry;

class Bootstrap implements BootstrapInterface
{
    
    protected $componentRegistry;
    protected $moduleName;
    protected $items;
    
    protected $path = null;
    
    public function __construct(
        ComponentRegistry $componentRegistry,
        $moduleName,
        array $items
    ) {
        $this->componentRegistry = $componentRegistry;
        $this->moduleName = $moduleName;
        $this->items = $items;
    }
    
    public function getPath()
    {
        
        if ($this->path === null) {
            
            $path = $this->componentRegistry->getPath(
                ComponentRegistry::MODULE,
                $this->moduleName
            );
            $path = implode(
                DIRECTORY_SEPARATOR,
                [$path, 'data', 'cms', 'wordpress']
            ) . DIRECTORY_SEPARATOR;
            
            $this->path = $path;
            
        }
        
        return $this->path;
        
    }
    
    public function process($item, $invoke = false)
    {
        
        $result = false;
        
        if (isset($this->items[$item])) {
            
            $path = $this->getPath();
            $result = $this->items[$item]->create([
                'path' => $path
            ])->read($invoke);
            
        }
        
        return $result;
        
    }
    
    public function __call($item, array $args = [])
    {
        return $this->process($item, isset($args[0]) && $args[0]);
    }
    
}
