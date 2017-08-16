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

use \Wwm\Blog\Cms\WordPress\Theme\Hook\Filter\AbstractFilter;

class StorageFactory
{
    
    protected $objectManager = null;
    protected $instanceName = null;
    protected $filters;
    
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        $instanceName = \Wwm\Blog\Cms\WordPress\Theme\Hook\Storage::class,
        array $filters = []
    ) {
        $this->objectManager = $objectManager;
        $this->instanceName = $instanceName;
        $this->filters = $filters;
    }
    
    public function create()
    {
        
        $filters = [];
        
        foreach ($this->filters as $filter) {
            
            $filterArgs = ['name' => $filter['name']];
            
            if (isset($filter['priority'])) {
                $filterArgs['priority'] = $filter['priority'];
            }
            
            if (isset($filter['argsCount'])) {
                $filterArgs['argsCount'] = $filter['argsCount'];
            } else {
                $filterArgs['argsCount'] = (new \ReflectionClass($filter['filter']))
                    ->getMethod('filter')
                    ->getNumberOfParameters();
            }
            
            $filterInstance = $this->objectManager->create($filter['filter'], $filterArgs);
            if (false == $filterInstance instanceof AbstractFilter) {
                throw new \InvalidArgumentException(
                    $filter['filter'] . ' is not an instance of ' . AbstractFilter::class
                );
            }
            
            $filters[$filter['name']][] = $filterInstance;
            
        }
        
        return $this->objectManager->create($this->instanceName, ['filters' => $filters]);
        
    }
    
}
