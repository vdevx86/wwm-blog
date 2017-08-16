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

namespace Wwm\Blog\Cms\WordPress\Theme\Hook\Filter;

class SidebarRegisterLeft extends AbstractFilter
{
    
    const SIDEBAR_ID = 'sidebar-left';
    
    public function filter()
    {
        $this->entryPoint->registerSidebar([
            'name' => $this->entryPoint->__('Left'),
            'id' => static::SIDEBAR_ID,
            'description' => $this->entryPoint->__('Add widgets here to appear in your sidebar.'),
            'before_widget' => $this->entryPoint->renderTemplate(['sidebar', 'left', 'beforeWidget']),
            'after_widget' => $this->entryPoint->renderTemplate(['sidebar', 'left', 'afterWidget']),
            'before_title' => $this->entryPoint->renderTemplate(['sidebar', 'left', 'beforeTitle']),
            'after_title' => $this->entryPoint->renderTemplate(['sidebar', 'left', 'afterTitle'])
        ]);
    }
    
}
