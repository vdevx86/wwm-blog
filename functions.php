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

// @codingStandardsIgnoreFile

function __(...$argc)
{
    
    $text = array_shift($argc);
    
    if (defined('ABSPATH')) {
        if (strpos(debug_backtrace(2, 1)[0]['file'], ABSPATH, 0) === 0) {
            if (isset($argc[0]) && is_string($argc[0])) {
                return ___($text, $argc[0]);
            }
            return ___($text);
        }
    }
    
    if (!empty($argc) && is_array($argc[0])) {
        $argc = $argc[0];
    }
    
    return new \Magento\Framework\Phrase($text, $argc);
    
}
