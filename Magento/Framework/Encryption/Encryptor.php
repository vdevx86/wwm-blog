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

namespace Wwm\Blog\Magento\Framework\Encryption;

class Encryptor extends \Magento\Framework\Encryption\Encryptor
{
    
    protected $keyVersion = 0;
    
    public function __construct(
        \Magento\Framework\Math\Random $random,
        $key
    ) {
        $this->random = $random;
        $this->keys = [$key];
    }
    
    public function setNewKey($key)
    {
        $this->validateKey($key);
        $this->keys = [$key];
        return $this;
    }
    
}
