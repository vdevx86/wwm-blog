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

namespace Wwm\Blog\Magento\Framework\View\Result;

class Page extends \Magento\Framework\View\Result\Page
{
    
    public function initPageConfigReader(
        $rendererFactory = \Wwm\Blog\Magento\Framework\View\Page\Config\RendererFactory::class
    ) {
        $this->pageConfigRenderer = \Magento\Framework\App\ObjectManager::getInstance()
            ->get($rendererFactory)
            ->create(['pageConfig' => $this->pageConfig]);
    }
    
}
