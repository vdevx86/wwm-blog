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

namespace Wwm\Blog\Cms\WordPress\Theme\LayerItem;

class Widgets extends AbstractLayerItem
{
    
    public function applyDynamicSidebarSpecificFilters($html)
    {
        
        $before = 'location.href = "';
        $after = '/?cat=" + dropdown.options[ dropdown.selectedIndex ].value;';
        
        $html = $this->entryPoint->strReplace(
            $before . $this->entryPoint->getHomeURLOriginal() . $after,
            $before . $this->entryPoint->getHomeURLNew() . $after,
            $html
        );
        
        return $html;
        
    }
    
    public function getDynamicSidebar($id)
    {
        return $this->entryPoint->applyDynamicSidebarSpecificFilters(
            $this->entryPoint->getContents(function ($id) {
                dynamic_sidebar($id);
            }, [$id])
        );
    }
    
    public function dynamicSidebar($id)
    {
        echo $this->entryPoint->getDynamicSidebar($id);
    }
    
    public function theDynamicSidebar($id)
    {
        $this->dynamicSidebar($id);
    }
    
}
