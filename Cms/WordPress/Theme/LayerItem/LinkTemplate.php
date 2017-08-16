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

class LinkTemplate extends AbstractLayerItem
{
    
    protected $homeURLOriginal;
    protected $homeURLNew;
    
    public function getHomeURLOriginal()
    {
        return $this->homeURLOriginal;
    }
    
    public function setHomeURLOriginal($homeURLOriginal)
    {
        $this->homeURLOriginal = $homeURLOriginal;
        return $this;
    }
    
    public function getHomeURLNew()
    {
        return $this->homeURLNew;
    }
    
    public function setHomeURLNew($homeURLNew)
    {
        $this->homeURLNew = $homeURLNew;
        return $this;
    }
    
}
