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

interface FileSystemInterface
{
    
    const FN_EXT = '.php';
    
    const DIR_ROOT = 'wordpress';
    const DIR_INCLUDES = 'wp-includes';
    
    // Files from the root directory
    const FN_INDEX = 'index';
    const FN_CONFIG = 'wp-config';
    const FN_SETTINGS = 'wp-settings';
    const FN_LOGIN = 'wp-login';
    const FN_LOAD = 'wp-load';
    
    // Files from the wp-includes directory
    const FN_PLUGIN = 'plugin';
    const FN_TRANSLATE = 'l10n';
    const FN_TPLDR = 'template-loader';
    
    public function getDirectoryRead();
    
}
