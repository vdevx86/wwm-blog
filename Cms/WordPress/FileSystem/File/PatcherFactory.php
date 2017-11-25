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

namespace Wwm\Blog\Cms\WordPress\FileSystem\File;

class PatcherFactory
{
    
    protected $objectManager = null;
    protected $instanceName = null;
    protected $patches;
    
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        $instanceName = \Wwm\Blog\Cms\WordPress\FileSystem\File\PatcherInterface::class,
        array $patches = []
    ) {
        $this->objectManager = $objectManager;
        $this->instanceName = $instanceName;
        $this->patches = $patches;
    }
    
    public function create()
    {
        return $this->objectManager->create(
            $this->instanceName,
            ['patches' => $this->patches]
        );
    }
    
}
