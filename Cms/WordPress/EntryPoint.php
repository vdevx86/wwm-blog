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

use Magento\Framework\Api\SimpleDataObjectConverter as Converter;

class EntryPoint
{
    
    protected $layerFactory;
    protected $layer = null;
    
    public function __construct(
        \Wwm\Blog\Cms\WordPress\Theme\LayerFactory $layerFactory
    ) {
        $this->layerFactory = $layerFactory;
    }
    
    public function __call($name, array $args = [])
    {
        
        if ($this->layer === null) {
            $this->layer = $this->layerFactory->create();
        }
        
        $result = false;
        
        if ($this->layer->match($name, $args)) {
            $result = $this->layer->getResult();
        } else {
            $name = Converter::camelCaseToSnakeCase($name);
            $result = $name(...$args);
        }
        
        return $result;
        
    }
    
}
