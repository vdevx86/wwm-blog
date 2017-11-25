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

class Patcher implements PatcherInterface
{
    
    protected $fileSystem;
    protected $patches;
    
    public function __construct(
        \Wwm\Blog\Cms\WordPress\FileSystemInterface $fileSystem,
        array $patches = []
    ) {
        $this->fileSystem = $fileSystem;
        $this->patches = $patches;
    }
    
    public function apply($name)
    {
        
        $result = false;
        
        if (isset($this->patches[$name])) {
            
            $directoryRead = $this->fileSystem->getDirectoryRead();
            
            $patch =& $this->patches[$name];
            $content = $directoryRead->readFile($patch->getFileName());
            $result = $patch->patch($content);
            
        }
        
        return $result;
        
    }
    
    public function __call($name, array $args = [])
    {
        return $this->apply($name);
    }
    
}
