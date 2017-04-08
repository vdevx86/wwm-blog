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

final class Theme extends \WWMT
{
    
    protected $_context;
    
    protected $_homeURL = null;
    protected $_homeURLNew = null;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct();
        $this->_context = $context;
        
    }
    
    public function getContext()
    {
        return $this->_context;
    }
    
    public function getHomeURL()
    {
        return $this->_homeURL;
    }
    
    public function setHomeURL($homeURL)
    {
        $this->_homeURL = $homeURL;
        return $this;
    }
    
    public function getHomeURLNew()
    {
        return $this->_homeURLNew;
    }
    
    public function setHomeURLNew($homeURLNew)
    {
        $this->_homeURLNew = $homeURLNew;
        return $this;
    }
    
    public function includeTemplateLoader()
    {
        require_once ABSPATH . WPINC . DIRECTORY_SEPARATOR . FileSystem::FN_TPLDR . FileSystem::FN_EXT;
        return $this;
    }
    
}
