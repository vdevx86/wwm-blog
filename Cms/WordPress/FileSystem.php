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

use Magento\Framework\Exception\FileSystemException;

class FileSystem
{
    
    const FN_EXT = '.php';
    
    const DIR_ROOT = 'wordpress';
    const DIR_INCLUDES = 'wp-includes';
    
    // Files from root directory
    const FN_INDEX = 'index';
    const FN_CONFIG = 'wp-config';
    const FN_SETTINGS = 'wp-settings';
    const FN_LOGIN = 'wp-login';
    const FN_LOAD = 'wp-load';
    
    // Files from wp-includes directory
    const FN_PLUGIN = 'plugin';
    const FN_TRANSLATE = 'l10n';
    const FN_TPLDR = 'template-loader';
    
    protected $config;
    protected $driverFile;
    
    protected $loadStatus = false;
    protected $installationPath;
    protected $includePath;
    
    public function __construct(
        \Wwm\Blog\Helper\Config $config,
        \Magento\Framework\Filesystem\Driver\File $driverFile
    ) {
        $this->config = $config;
        $this->driverFile = $driverFile;
    }
    
    public function getLoadStatus() { return $this->loadStatus; }
    public function getInstallationPath() { return $this->installationPath; }
    public function getIncludePath() { return $this->includePath; }
    
    public function validateDirectory($path)
    {
        return $this->driverFile->isDirectory($path)
            && $this->driverFile->isReadable($path);
    }
    
    public function validateFile($file)
    {
        return $this->driverFile->isFile($file)
            && $this->driverFile->isReadable($file);
    }
    
    public function load()
    {
        
        if (!$this->getLoadStatus()) {
            
            $installationPath = BP . DIRECTORY_SEPARATOR . ($this->config->getInstallationPath() ?: static::DIR_ROOT);
            if (!$this->validateDirectory($installationPath)) {
                throw new FileSystemException(__(
                    'WordPress installation path does not exists or not readable: %1',
                    $installationPath
                ));
            }
            
            $installationPath .= DIRECTORY_SEPARATOR;
            $this->installationPath = $installationPath;
            
            $fileName = $installationPath . static::FN_INDEX . static::FN_EXT;
            if (!$this->validateFile($fileName)) {
                throw new FileSystemException(__(
                    'WordPress index php file not founded or not readable: %1',
                    $fileName
                ));
            }
            
            $fileName = $installationPath . static::FN_CONFIG . static::FN_EXT;
            if (!$this->validateFile($fileName)) {
                throw new FileSystemException(__(
                    'WordPress configuration php file not founded or not readable: %1',
                    $fileName
                ));
            }
            $fileName = $installationPath . static::FN_SETTINGS . static::FN_EXT;
            if (!$this->validateFile($fileName)) {
                throw new FileSystemException(__(
                    'WordPress settings php file not founded or not readable: %1',
                    $fileName
                ));
            }
            
            $includePath = $installationPath . static::DIR_INCLUDES;
            if (!$this->validateDirectory($includePath)) {
                throw new FileSystemException(__(
                    'WordPress include path does not exists or not readable: %1',
                    $includePath
                ));
            }
            
            $includePath .= DIRECTORY_SEPARATOR;
            $this->includePath = $includePath;
            
            $fileName = $includePath . static::FN_TRANSLATE . static::FN_EXT;
            if (!$this->validateFile($fileName)) {
                throw new FileSystemException(__(
                    'WordPress localizations php file not founded or not readable: %1',
                    $fileName
                ));
            }
            
            $this->loadStatus = true;
            
        }
        
        return $this;
        
    }
    
}
