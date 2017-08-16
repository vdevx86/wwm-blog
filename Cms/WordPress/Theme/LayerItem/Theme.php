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

class Theme extends AbstractLayerItem
{
    
    public function enableTitleTagSupport()
    {
        return $this->entryPoint->addThemeSupport('title-tag');
    }
    
    public function enableAutomaticFeedLinks()
    {
        return $this->entryPoint->addThemeSupport('automatic-feed-links');
    }
    
    public function enablePostFormats()
    {
        return $this->entryPoint->addThemeSupport(
            'post-formats',
            ['aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat']
        );
    }
    
    public function enableHTML5()
    {
        return $this->entryPoint->addThemeSupport(
            'html5',
            ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']
        );
    }
    
    public function enableCSRV()
    {
        return $this->entryPoint->addThemeSupport('customize-selective-refresh-widgets');
    }
    
    public function enablePostThumbnails()
    {
        return $this->entryPoint->addThemeSupport('post-thumbnails');
    }
    
    public function enableThemeFeatures()
    {
        return $this->entryPoint->enableTitleTagSupport()
            && $this->entryPoint->enableAutomaticFeedLinks()
            && $this->entryPoint->enablePostFormats()
            && $this->entryPoint->enableHTML5()
            && $this->entryPoint->enableCSRV()
            && $this->entryPoint->enablePostThumbnails();
    }
    
}
