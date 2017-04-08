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
    
    // Files from wp-includes direcotry
    const FN_PLUGIN = 'plugin';
    const FN_TRANSLATE = 'l10n';
    const FN_TPLDR = 'template-loader';
    
    // Files from theme directory
    const FN_CLASS = 'class';
    
    protected $_loadStatus = false;
    protected $_config;
    
    protected $_installationPath;
    protected $_includePath;
    protected $_indexFile;
    
    public function __construct(
        \Wwm\Blog\Helper\Config $config
    ) {
        $this->_config = $config;
    }
    
    public function getLoadStatus()
    {
        return $this->_loadStatus;
    }
    
    public function getConfig()
    {
        return $this->_config;
    }
    
    public function getInstallationPath()
    {
        return $this->_installationPath;
    }
    
    public function getIncludePath()
    {
        return $this->_includePath;
    }
    
    public static function validateDirectory($path)
    {
        return @is_dir($path) && @is_readable($path);
    }
    
    public static function validateFile($file)
    {
        return @is_file($file) && @is_readable($file);
    }
    
    public function load()
    {
        
        if ($this->getLoadStatus()) {
            return $this;
        }
        
        $installationPath = BP . DIRECTORY_SEPARATOR . ($this->getConfig()->getInstallationPath() ?: static::DIR_ROOT);
        if (!static::validateDirectory($installationPath)) {
            throw new FileSystemException(__('WordPress installation path does not exists or not readable: %1', $installationPath));
        }
        
        $installationPath .= DIRECTORY_SEPARATOR;
        $this->_installationPath = $installationPath;
        
        $fileName = $installationPath . static::FN_INDEX . static::FN_EXT;
        if (!static::validateFile($fileName)) {
            throw new FileSystemException(__('WordPress index php file not founded or not readable: %1', $fileName));
        }
        
        $fileName = $installationPath . static::FN_CONFIG . static::FN_EXT;
        if (!static::validateFile($fileName)) {
            throw new FileSystemException(__('WordPress configuration php file not founded or not readable: %1', $fileName));
        }
        $fileName = $installationPath . static::FN_SETTINGS . static::FN_EXT;
        if (!static::validateFile($fileName)) {
            throw new FileSystemException(__('WordPress settings php file not founded or not readable: %1', $fileName));
        }
        
        $includePath = $installationPath . static::DIR_INCLUDES;
        if (!static::validateDirectory($includePath)) {
            throw new FileSystemException(__('WordPress include path does not exists or not readable: %1', $includePath));
        }
        
        $includePath .= DIRECTORY_SEPARATOR;
        $this->_includePath = $includePath;
        
        $fileName = $includePath . static::FN_TRANSLATE . static::FN_EXT;
        if (!static::validateFile($fileName)) {
            throw new FileSystemException(__('WordPress localizations php file not founded or not readable: %1', $fileName));
        }
        
        $this->_loadStatus = true;
        return $this;
        
    }
    
    public function checkLoadStatus()
    {
        if (!$this->getLoadStatus()) {
            throw new FileSystemException(__('WordPress FileSystem object not loaded'));
        }
        return $this;
    }
    
}
