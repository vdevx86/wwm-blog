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

use Magento\Framework\Exception\FileSystemException;
use Wwm\Blog\Cms\WordPress\FileSystemInterface;

class File
{
    
    protected $fileSystem;
    protected $driverFile;
    
    public function __construct(
        FileSystemInterface $fileSystem,
        \Magento\Framework\Filesystem\Driver\File $driverFile
    ) {
        $this->fileSystem = $fileSystem;
        $this->driverFile = $driverFile;
    }
    
    public function readFile($fileName)
    {
        
        $fileName .= FileSystemInterface::FN_EXT;
        $installationPath = $this->fileSystem->getDirectoryRead()
            ->getAbsolutePath();
        
        $content = $this->driverFile->fileGetContents(
            $installationPath . $fileName
        );
        
        if (!$content) {
            throw new FileSystemException(__('Error reading WordPress file: %1', $fileName));
        }
        
        return $content;
        
    }
    
}
