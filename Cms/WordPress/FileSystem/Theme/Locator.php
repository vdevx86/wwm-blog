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

namespace Wwm\Blog\Cms\WordPress\FileSystem\Theme;

use Magento\Framework\Module\Dir;

class Locator
{
    
    protected $moduleReader;
    protected $moduleName;
    
    public function __construct(
        \Magento\Framework\Module\Dir\Reader $moduleReader,
        $moduleName
    ) {
        $this->moduleReader = $moduleReader;
        $this->moduleName = $moduleName;
    }
    
    public function getLocation()
    {
        return implode(DIRECTORY_SEPARATOR, [
            $this->moduleReader->getModuleDir(
                Dir::MODULE_VIEW_DIR,
                $this->moduleName
            ),
            'frontend',
            'templates',
            'theme'
        ]);
    }
    
}
