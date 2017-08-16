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

namespace Wwm\Blog\Magento\Framework\App\Request\Http\Uri;

class Parser implements ParserInterface
{
    
    const IDX_ROUTE = 0;
    const IDX_QUERY = 1;
    
    protected $context;
    protected $uri = null;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->context = $context;
        $this->_construct();
    }
    
    protected function _construct()
    {
        $uri = $this->context->getRequest()->getRequestUri();
        $this->uri = $this->parse($uri);
    }
    
    public function parse($uri)
    {
        if ($uri = ltrim($uri, static::DELIMITER)) {
            $uri = explode(static::DELIMITER, $uri, 2);
            if (!isset($uri[static::IDX_QUERY])) {
                $uri[static::IDX_QUERY] = '';
            }
        }
        return $uri;
    }
    
    public function getUri()
    {
        return $this->uri;
    }
    
    public function getRoute()
    {
        return $this->uri[static::IDX_ROUTE];
    }
    
    public function getQuery()
    {
        return $this->uri[static::IDX_QUERY];
    }
    
}
