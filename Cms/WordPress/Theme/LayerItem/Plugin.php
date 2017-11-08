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

use Wwm\Blog\Cms\WordPress\Theme\HookInterface;

class Plugin extends AbstractLayerItem
{
    
    public function addFilter(
        $tag,
        $function,
        $priority = HookInterface::DEFAULT_PRIORITY,
        $acceptedArgs = HookInterface::DEFAULT_ARGS_COUNT
    ) {
        return add_filter($tag, $function, $priority, $acceptedArgs);
    }
    
    public function removeHeadAction($name, $priority = HookInterface::DEFAULT_PRIORITY)
    {
        return $this->entryPoint->removeAction('wp_head', $name, $priority);
    }
    
    public function removeHeadActions()
    {
        return $this->entryPoint->removeHeadAction('noindex', 1)
            && $this->entryPoint->removeHeadAction('wp_no_robots')
            && $this->entryPoint->removeHeadAction('wp_generator')
            && $this->entryPoint->removeHeadAction('feed_links_extra', 3)
            && $this->entryPoint->removeHeadAction('wp_oembed_add_discovery_links')
            && $this->entryPoint->removeHeadAction('wp_oembed_add_host_js')
            && $this->entryPoint->removeHeadAction('rest_output_link_wp_head')
            && $this->entryPoint->removeHeadAction('rsd_link')
            && $this->entryPoint->removeHeadAction('wlwmanifest_link');
    }
    
}
