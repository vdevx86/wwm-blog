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

namespace Wwm\Blog\Cms\WordPress\Load;

use Wwm\Blog\Cms\WordPress\FileSystem;

class Type
{
    
    const LT_DEFAULT = 0;
    const LT_LOGIN = 1;
    
    protected $config;
    protected $uriParser;
    
    protected $type = self::LT_DEFAULT;
    
    public function __construct(
        \Wwm\Blog\Helper\Config $config,
        \Wwm\Blog\Magento\Framework\App\Request\Http\Uri\ParserInterface $uriParser
    ) {
        $this->config = $config;
        $this->uriParser = $uriParser;
        $this->_construct();
    }
    
    protected function _construct()
    {
        $query = $this->uriParser->getQuery();
        if (strpos($query, FileSystem::FN_LOGIN . FileSystem::FN_EXT) === 0) {
            $this->type = static::LT_LOGIN;
        }
    }
    
    public function getType()
    {
        return $this->type;
    }
    
}
