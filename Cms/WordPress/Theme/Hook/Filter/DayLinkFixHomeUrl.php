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

namespace Wwm\Blog\Cms\WordPress\Theme\Hook\Filter;

class DayLinkFixHomeUrl extends AbstractFilter
{
    
    public function filter($daylink, $year, $month, $day)
    {
        return str_replace(
            $this->entryPoint->getHomeURLOriginal(),
            $this->entryPoint->getHomeURLNew(),
            $daylink
        );
    }
    
}