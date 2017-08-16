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

// @codingStandardsIgnoreFile

namespace Wwm\Blog\Cms\WordPress\Theme\LayerItem;

class PostTemplate extends AbstractLayerItem
{
    
    public function theId()
    {
        the_ID();
    }
    
    public function getTheId()
    {
        return get_the_ID();
    }
    
    public function getPostListClass($I)
    {
        
        $classes = ['post-item', 'item', 'item' . $I];
        
        if ($I & 1) {
            $classes[] = 'odd';
        }
        if ($I == 1) {
            $classes[] = 'first';
        }
        
        global $wp_query;
        if ($I == $wp_query->post_count) {
            $classes[] = 'last';
        }
        
        return implode(' ', $classes);
        
    }
    
    public function thePostListClass($I)
    {
        echo $this->entryPoint->getPostListClass($I);
    }
    
}
