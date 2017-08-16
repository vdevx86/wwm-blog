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

class CategoryTemplate extends AbstractLayerItem
{
    
    public function thePostListTags()
    {
        $this->entryPoint->theTags($this->entryPoint->renderTemplate(['tag', 'before']));
    }
    
    public function theCategory($separator = ', ', $parents = '', $postId = false)
    {
        the_category($separator, $parents, $postId);
    }
    
}
