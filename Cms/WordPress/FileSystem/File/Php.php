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

class Php extends \Wwm\Blog\Cms\WordPress\FileSystem\File
{
    
    const FNPART_PHPTAG = '<?php';
    
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
    
    public function readFile($fileName)
    {
        $content = parent::readFile($fileName);
        return static::removePhpTag($content);
    }
    
}
