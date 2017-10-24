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

namespace Wwm\Blog\Cms\WordPress\Bootstrap;

interface FileInterface
{
    
    const ENC_MODE = 'deflate';
    const ENC_DMTR = ':';
    const ENC_PRM1 = '0';
    const ENC_PRM2 = '2';
    
    const SC_BEFORE = '{{';
    const SC_AFTER = '}}';
    
    public function getHash();
    public function getPath();
    public function getSource();
    
    public function read();
    
}
