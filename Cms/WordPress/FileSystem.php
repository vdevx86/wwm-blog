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

namespace Wwm\Blog\Cms\WordPress;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\DriverPool;
use Magento\Framework\Exception\FileSystemException;

class FileSystem implements FileSystemInterface
{
    
    protected $directoryList;
    protected $readFactory;
    protected $config;
    
    protected $directoryRead = null;
    
    public function __construct(
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory,
        \Wwm\Blog\Cms\WordPress\ConfigInterface $config
    ) {
        $this->directoryList = $directoryList;
        $this->readFactory = $readFactory;
        $this->config = $config;
    }
    
    public function getDirectoryRead()
    {
        
        if ($this->directoryRead === null) {
            
            $installationDirectory = $this->config->getInstallationPath();
            if (!$installationDirectory) {
                $installationDirectory = self::DIR_ROOT;
            }
            
            $installationPath = $this->directoryList->getPath(DirectoryList::ROOT) .
                DIRECTORY_SEPARATOR . $installationDirectory;
            
            $directoryRead = $this->readFactory->create(
                $installationPath,
                DriverPool::FILE
            );
            
            if (!$directoryRead->isDirectory()) {
                throw new FileSystemException(__(
                    'WordPress installation path is not a directory: %1',
                    $directoryRead->getAbsolutePath()
                ));
            }
            if (!$directoryRead->isReadable()) {
                throw new FileSystemException(__(
                    'WordPress installation path is not readable: %1',
                    $directoryRead->getAbsolutePath()
                ));
            }
            
            if (!$directoryRead->isDirectory(self::DIR_INCLUDES)) {
                throw new FileSystemException(__(
                    'WordPress includes path is not a directory: %1',
                    $directoryRead->getAbsolutePath() . self::DIR_INCLUDES
                ));
            }
            if (!$directoryRead->isReadable(self::DIR_INCLUDES)) {
                throw new FileSystemException(__(
                    'WordPress includes path is not readable: %1',
                    $directoryRead->getAbsolutePath() . self::DIR_INCLUDES
                ));
            }
            
            $fileNames = [
                self::FN_INDEX,
                self::FN_CONFIG,
                self::FN_SETTINGS,
                self::DIR_INCLUDES . DIRECTORY_SEPARATOR . self::FN_TRANSLATE
            ];
            
            foreach ($fileNames as $fileName) {
                $fileName .= self::FN_EXT;
                if (!$directoryRead->isFile($fileName)) {
                    throw new FileSystemException(__(
                        'WordPress file is not a file: %1',
                        $directoryRead->getAbsolutePath() . $fileName
                    ));
                }
                if (!$directoryRead->isReadable($fileName)) {
                    throw new FileSystemException(__(
                        'WordPress file is not readable: %1',
                        $directoryRead->getAbsolutePath() . $fileName
                    ));
                }
            }
            
            $this->directoryRead = $directoryRead;
            
        }
        
        return $this->directoryRead;
        
    }
    
}
