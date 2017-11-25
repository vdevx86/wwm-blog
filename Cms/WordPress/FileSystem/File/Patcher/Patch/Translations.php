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

namespace Wwm\Blog\Cms\WordPress\FileSystem\File\Patcher\Patch;

use Wwm\Blog\Cms\WordPress\FileSystemInterface;
use Magento\Framework\Exception\FileSystemException;

class Translations extends \Wwm\Blog\Cms\WordPress\FileSystem\File\Patcher\AbstractPatch
{
    
    const FILENAME = FileSystemInterface::DIR_INCLUDES
                   . DIRECTORY_SEPARATOR
                   . FileSystemInterface::FN_TRANSLATE
                   . FileSystemInterface::FN_EXT;
    
    public function patch($content)
    {
        
        $result = false;
        $count = 0;
        
        $content = parent::patch($content);
        $content = str_replace(
            'function __(',
            'function ___(',
            $content,
            $count
        );
        
        if ($content && $count == 1) {
            $result = $content;
        } else {
            throw new FileSystemException(__(
                'Error patching WordPress translations file: %1',
                $this->getFileName()
            ));
        }
        
        return $result;
        
    }
    
}
