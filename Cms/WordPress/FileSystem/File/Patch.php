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

use Magento\Framework\Exception\FileSystemException;
use Wwm\Blog\Cms\WordPress\FileSystem;

class Patch extends \Wwm\Blog\Cms\WordPress\FileSystem\File
{
    
    const PT_CONFIG = 0;
    const PT_TRANSLATIONS = 1;
    const PT_SETTINGS = 2;
    const PT_LOGIN = 3;
    
    protected $_patchCount = null;
    
    public function setPatchCount($patchCount)
    {
        $this->_patchCount = $patchCount;
        return $this;
    }
    
    public function getPatchCount()
    {
        return $this->_patchCount;
    }
    
    public function patch($content, $from, $to = '')
    {
        
        $count = 0;
        $result = str_replace($from, $to, $content, $count);
        
        $this->setPatchCount($count);
        if ($result && $count > 0) {
            return $result;
        }
        
        return false;
        
    }
    
    public function getPatchedFile($patchType)
    {
        
        switch ($patchType) {
            case static::PT_CONFIG:
                if ($content = $this->readPhpFile(FileSystem::FN_CONFIG)) {
                    if ($content = $this->patch($content, ['require_once', 'include_once', 'require', 'include'])) {
                        return $content;
                    }
                    throw new FileSystemException(__('Error patching WordPress configuration file: %1', FileSystem::FN_CONFIG . FileSystem::FN_EXT));
                }
                break;
            case static::PT_TRANSLATIONS:
                if ($content = $this->readPhpFile(FileSystem::DIR_INCLUDES . DIRECTORY_SEPARATOR . FileSystem::FN_TRANSLATE)) {
                    if ($content = $this->patch($content, 'function __(', 'function ___(')) {
                        return $content;
                    }
                    throw new FileSystemException(__('Error patching WordPress translations file: %1', FileSystem::DIR_INCLUDES . DIRECTORY_SEPARATOR . FileSystem::FN_TRANSLATE . FileSystem::FN_EXT));
                }
                break;
            case static::PT_SETTINGS:
                if ($content = $this->readPhpFile(FileSystem::FN_SETTINGS)) {
                    if ($content = $this->patch($content, 'require_once( ABSPATH . WPINC . \'/' . FileSystem::FN_TRANSLATE . FileSystem::FN_EXT . '\' );')) {
                        return $content;
                    }
                    throw new FileSystemException(__('Error patching WordPress settings file: %1', FileSystem::FN_SETTINGS . FileSystem::FN_EXT));
                }
                break;
            case static::PT_LOGIN:
                if ($content = $this->readPhpFile(FileSystem::FN_LOGIN)) {
                    if ($content = $this->patch($content, 'require( dirname(__FILE__) . \'/' . FileSystem::FN_LOAD . FileSystem::FN_EXT . '\' );')) {
                        return $content;
                    }
                    throw new FileSystemException(__('Error patching WordPress login file: %1', FileSystem::FN_LOGIN . FileSystem::FN_EXT));
                }
        }
        
        return false;
        
    }
    
}
