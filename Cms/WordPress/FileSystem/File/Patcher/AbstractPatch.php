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

namespace Wwm\Blog\Cms\WordPress\FileSystem\File\Patcher;

abstract class AbstractPatch implements PatchInterface
{
    
    public function getFileName()
    {
        return static::FILENAME;
    }
    
    public static function removePhpTag($content)
    {
        if (strpos($content, '<?php') === 0) {
            $I = 5;
            do {
                $content[--$I] = ' ';
            } while ($I);
        }
        return $content;
    }
    
    public function patch($content)
    {
        $content = static::removePhpTag($content);
        return $content;
    }
    
}
