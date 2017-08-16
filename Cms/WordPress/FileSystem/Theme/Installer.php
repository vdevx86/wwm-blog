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

use Wwm\Blog\Cms\WordPress\Theme;
use Wwm\Blog\Cms\WordPress\FileSystem\Theme\Locator as ThemeLocator;
use Magento\Framework\Exception\FileSystemException;

class Installer
{
    
    protected $fileSystem;
    protected $themeLocator;
    
    public function __construct(
        \Composer\Util\Filesystem $fileSystem,
        ThemeLocator $themeLocator
    ) {
        $this->fileSystem = $fileSystem;
        $this->themeLocator = $themeLocator;
    }
    
    public function install()
    {
        
        $to = get_theme_root() . DIRECTORY_SEPARATOR . Theme::NAME;
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
        
        return $this;
        
    }
    
}
