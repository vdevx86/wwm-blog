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

namespace Wwm\Blog\Cms\WordPress\FileSystem\Theme;

use Magento\Framework\Exception\FileSystemException;
use Wwm\Blog\Cms\WordPress\ThemeInterface;

class Install extends \Wwm\Blog\Cms\WordPress\FileSystem\Theme
{
    
    protected $entryPoint;
    protected $fileSystem;
    protected $directoryWriteFactory;
    protected $bootstrap;
    protected $themeLocator;
    
    public function __construct(
        \Wwm\Blog\Cms\WordPress\EntryPoint $entryPoint,
        \Composer\Util\Filesystem $fileSystem,
        \Magento\Framework\Filesystem\Directory\WriteFactory $directoryWriteFactory,
        \Wwm\Blog\Cms\WordPress\Theme\Install\Bootstrap $bootstrap,
        \Wwm\Blog\Cms\WordPress\FileSystem\Theme\Locator $themeLocator
    ) {
        $this->entryPoint = $entryPoint;
        $this->fileSystem = $fileSystem;
        $this->directoryWriteFactory = $directoryWriteFactory;
        $this->bootstrap = $bootstrap;
        $this->themeLocator = $themeLocator;
    }
    
    public function installParentTheme()
    {
        
        $to = $this->entryPoint->getThemeRoot()
            . DIRECTORY_SEPARATOR
            . ThemeInterface::NAME_PARENT;
        
        $directoryWrite = $this->directoryWriteFactory->create($to);
        
        $directoryWrite->delete();
        $directoryWrite->create();
        
        $files = [];
        
        $files[] = $this->bootstrap->functions();
        $files[] = $this->bootstrap->index();
        $files[] = $this->bootstrap->stylesheet();
        
        foreach ($files as $file) {
            $directoryWrite->writeFile(
                $file->getDestination(),
                $file->getContents()
            );
        }
        
    }
    
    public function installTheme()
    {
        
        $to = $this->entryPoint->getThemeRoot()
            . DIRECTORY_SEPARATOR
            . ThemeInterface::NAME;
        
        if ($this->fileSystem->isSymlinkedDirectory($to)) {
            $this->fileSystem->unlink($to);
        }
        
        $from = $this->themeLocator->getLocation();
        if (!$this->fileSystem->relativeSymlink($from, $to)) {
            throw new FileSystemException(__(
                'Could not install WordPress theme. Error creating symlink: %1 => %2',
                $from,
                $to
            ));
        }
        
    }
    
    public function install()
    {
        $this->installParentTheme();
        $this->installTheme();
        return $this;
    }
    
}
