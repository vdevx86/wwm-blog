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

namespace Wwm\Blog\Cms\WordPress;

interface ThemeInterface
{
    
    const NAME_PARENT = 'wwm-basic';
    const NAME = 'wwm';
    
    const AREA_CODE = 'frontend';
    const TEXT_DOMAIN_DEFAULT = 'default';
    
    const SUBTEMPLATE_PREFIX = 'templates';
    const FN_EXT = '.phtml';
    
}
