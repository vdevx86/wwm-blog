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

namespace Wwm\Blog\Cms\WordPress\Theme\Template;

class Parameters
{
    
    const KEY = 'template_parameters';
    
    protected $registry;
    
    public function __construct(
        \Magento\Framework\Registry $registry
    ) {
        $this->registry = $registry;
    }
    
    public function getRegistry()
    {
        return $this->registry;
    }
    
    public function get()
    {
        return $this->registry->registry(static::KEY);
    }
    
    public function set(\Magento\Framework\DataObject $templateParameters)
    {
        $this->registry->register(static::KEY, $templateParameters, true);
        return $this;
    }
    
    public function clear()
    {
        $this->registry->unregister(static::KEY);
        return $this;
    }
    
}
