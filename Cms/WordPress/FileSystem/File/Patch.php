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
    
    protected $patchCount = null;
    
    public function setPatchCount($patchCount) { $this->patchCount = $patchCount; return $this; }
    public function getPatchCount() { return $this->patchCount; }
    
    public function patch($content, $from, $to = '')
    {
        
        $result = false;
        
        $count = 0;
        $content = str_replace($from, $to, $content, $count);
        
        $this->setPatchCount($count);
        if ($content && $count > 0) {
            $result = $content;
        }
        
        return $result;
        
    }
    
    public function patchConfigurationFile()
    {
        
        $result = false;
        
        if ($content = $this->readPhpFile(FileSystem::FN_CONFIG)) {
            if ($content = $this->patch($content, ['require_once', 'include_once', 'require', 'include'])) {
                $result = $content;
            } else {
                throw new FileSystemException(__(
                    'Error patching WordPress configuration file: %1',
                    FileSystem::FN_CONFIG . FileSystem::FN_EXT
                ));
            }
        }
        
        return $result;
        
    }
    
    public function patchTranslationsFile()
    {
        
        $result = false;
        
        if ($content = $this->readPhpFile(FileSystem::DIR_INCLUDES . DIRECTORY_SEPARATOR . FileSystem::FN_TRANSLATE)) {
            if ($content = $this->patch($content, 'function __(', 'function ___(')) {
                $result = $content;
            } else {
                throw new FileSystemException(__(
                    'Error patching WordPress translations file: %1',
                    FileSystem::DIR_INCLUDES . DIRECTORY_SEPARATOR . FileSystem::FN_TRANSLATE . FileSystem::FN_EXT
                ));
            }
        }
        
        return $result;
        
    }
    
    public function patchSettingsFile()
    {
        
        $result = false;
        
        if ($content = $this->readPhpFile(FileSystem::FN_SETTINGS)) {
            $content = $this->patch(
                $content,
                'require_once( ABSPATH . WPINC . \'/' .FileSystem::FN_TRANSLATE . FileSystem::FN_EXT . '\' );'
            );
            if ($content) {
                $result = $content;
            } else {
                throw new FileSystemException(__(
                    'Error patching WordPress settings file: %1',
                    FileSystem::FN_SETTINGS . FileSystem::FN_EXT
                ));
            }
        }
        
        return $result;
        
    }
    
    public function patchLoginFile()
    {
        
        $result = false;
        
        if ($content = $this->readPhpFile(FileSystem::FN_LOGIN)) {
            $content = $this->patch(
                $content,
                'require( dirname(__FILE__) . \'/' . FileSystem::FN_LOAD . FileSystem::FN_EXT . '\' );'
            );
            if ($content) {
                $result = $content;
            } else {
                throw new FileSystemException(__(
                    'Error patching WordPress login file: %1',
                    FileSystem::FN_LOGIN . FileSystem::FN_EXT
                ));
            }
        }
        
        return $result;
        
    }
    
    public function getPatchedFile($patchType)
    {
        
        $result = false;
        
        switch ($patchType) {
            case static::PT_CONFIG:
                $result = $this->patchConfigurationFile();
                break;
            case static::PT_TRANSLATIONS:
                $result = $this->patchTranslationsFile();
                break;
            case static::PT_SETTINGS:
                $result = $this->patchSettingsFile();
                break;
            case static::PT_LOGIN:
                $result = $this->patchLoginFile();
        }
        
        return $result;
        
    }
    
}
