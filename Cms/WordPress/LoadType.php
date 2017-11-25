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

class LoadType implements LoadTypeInterface
{
    
    protected $uriParser;
    protected $type = self::LT_DEFAULT;
    
    public function __construct(
        \Wwm\Blog\Magento\Framework\App\Request\Http\Uri\ParserInterface $uriParser
    ) {
        $this->uriParser = $uriParser;
        $this->_construct();
    }
    
    protected function _construct()
    {
        $this->parse();
    }
    
    public function parse()
    {
        
        $query = $this->uriParser->getQuery();
        $fileName = FileSystemInterface::FN_LOGIN . FileSystemInterface::FN_EXT;
        
        if (strpos($query, $fileName) === 0) {
            $this->type = self::LT_LOGIN;
        }
        
        return $this;
        
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function isDefault()
    {
        return $this->type == self::LT_DEFAULT;
    }
    
    public function isLogin()
    {
        return $this->type == self::LT_LOGIN;
    }
    
    public function toFileName()
    {
        if ($this->type == self::LT_LOGIN) {
            $result = FileSystemInterface::FN_LOGIN;
        } else {
            $result = FileSystemInterface::FN_INDEX;
        }
        $result .= FileSystemInterface::FN_EXT;
        return $result;
    }
    
}
