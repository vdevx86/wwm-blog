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

namespace Wwm\Blog\Cms\WordPress\Theme;

class Layer
{
    
    protected $layerItems;
    protected $result = null;
    
    public function __construct(
        array $layerItems = []
    ) {
        $this->layerItems = $layerItems;
    }
    
    public function getLayerItems()
    {
        return $this->layerItems;
    }
    
    public function getResult()
    {
        return $this->result;
    }
    
    public function match($name, array $args = [])
    {
        
        $this->result = null;
        $result = false;
        
        foreach ($this->layerItems as $layerItem) {
            $reflectionClass = new \ReflectionClass($layerItem);
            if (
                    $reflectionClass->hasMethod($name)
                &&  $reflectionClass->getMethod($name)->isPublic()
            ) {
                $this->result = $layerItem->{$name}(...$args);
                $result = true;
                break;
            }
        }
        
        return $result;
        
    }
    
}
