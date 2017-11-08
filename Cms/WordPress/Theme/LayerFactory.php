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

class LayerFactory
{
    
    protected $objectManager = null;
    protected $instanceName = null;
    protected $layerItems;
    
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        $instanceName = \Wwm\Blog\Cms\WordPress\Theme\Layer::class,
        array $layerItems = []
    ) {
        $this->objectManager = $objectManager;
        $this->instanceName = $instanceName;
        $this->layerItems = $layerItems;
    }
    
    public function create()
    {
        $layerItems = [];
        foreach ($this->layerItems as $layerItemName => $layerItem) {
            $layerItems[$layerItemName] = $this->objectManager->get($layerItem);
        }
        return $this->objectManager->create($this->instanceName, ['layerItems' => $layerItems]);
    }
    
}
