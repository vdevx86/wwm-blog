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

namespace Wwm\Blog\Cms\WordPress\Theme\Hook;

class Storage
{
    
    protected $entryPoint;
    protected $filters;
    
    public function __construct(
        \Wwm\Blog\Cms\WordPress\EntryPoint $entryPoint,
        array $filters = []
    ) {
        $this->entryPoint = $entryPoint;
        $this->filters = $filters;
        $this->_construct();
    }
    
    protected function _construct()
    {
        foreach ($this->filters as $tag => $filters) {
            foreach ($filters as $filter) {
                $this->entryPoint->addFilter(
                    $tag,
                    [$filter, 'filter'],
                    $filter->getName(),
                    $filter->getPriority()
                );
            }
        }
    }
    
}
