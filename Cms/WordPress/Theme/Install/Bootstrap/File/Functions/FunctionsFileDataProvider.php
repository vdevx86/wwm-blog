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

namespace Wwm\Blog\Cms\WordPress\Theme\Install\Bootstrap\File\Functions;

use Wwm\Blog\Cms\WordPress\Bootstrap\FileDataProviderInterface;
use Wwm\Blog\Cms\WordPress\ThemeInterface;

class FunctionsFileDataProvider implements FileDataProviderInterface
{
    
    protected $data;
    protected $mathRandom;
    
    public function __construct(
        \Magento\Framework\DataObject $data,
        \Magento\Framework\Math\Random $mathRandom
    ) {
        $this->data = $data;
        $this->mathRandom = $mathRandom;
    }
    
    public function getData()
    {
        
        $data = $this->data;
        
        $data->setUniqueId($this->mathRandom->getUniqueHash())
            ->setAreaCode(ThemeInterface::AREA_CODE);
        
        return $data->getData();
        
    }
    
}
