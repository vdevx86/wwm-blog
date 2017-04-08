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

namespace Wwm\Blog\Cms\WordPress\FileSystem;

use Wwm\Blog\Cms\WordPress\FileSystem;

class File
{
    
    const FNPART_PHPTAG = '<?php';
    
    protected $_fileSystem;
    
    public function __construct(
        FileSystem $fileSystem
    ) {
        $this->_fileSystem = $fileSystem;
    }
    
    public function getFileSystem()
    {
        return $this->_fileSystem->load();
    }
    
    public static function removePhpTag($content)
    {
        
        if (strpos($content, static::FNPART_PHPTAG) === 0) {
            $I = 5;
            do {
                $content[--$I] = ' ';
            } while ($I);
        }
        
        return $content;
        
    }
    
    public function readPhpFile($fileName)
    {
        
        $fileSystem = $this->getFileSystem();
        
        $fileName .= FileSystem::FN_EXT;
        $content = @file_get_contents($fileSystem->getInstallationPath() . $fileName);
        
        if (!$content) {
            throw new FileSystemException(__('Error reading WordPress file: %1', $fileName));
        }
        
        return $this->removePhpTag($content);
        
    }
    
}
