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

// @codingStandardsIgnoreFile

namespace Wwm\Blog\Cms\WordPress;

final class Theme extends \WWMT
{
    
    protected $context;
    
    protected $homeURL = null;
    protected $homeURLNew = null;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct();
        $this->context = $context;
    }
    
    public function getContext() { return $this->context; }
    
    public function getHomeURL() { return $this->homeURL; }
    public function setHomeURL($homeURL) { $this->homeURL = $homeURL; return $this; }
    public function getHomeURLNew() { return $this->homeURLNew; }
    public function setHomeURLNew($homeURLNew) { $this->homeURLNew = $homeURLNew; return $this; }
    
    public function includeTemplateLoader()
    {
        require_once ABSPATH . WPINC . DIRECTORY_SEPARATOR . FileSystem::FN_TPLDR . FileSystem::FN_EXT;
        return $this;
    }
    
}
