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

namespace Wwm\Blog\Block;

class Index extends \Magento\Framework\View\Element\Template
{
    
    protected $_wp;
    protected $_theme;
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Wwm\Blog\Cms\WordPress $wp,
        \Wwm\Blog\Cms\WordPress\Theme $theme,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_wp = $wp;
        $this->_theme = $theme;
    }
    
    public function getTheme()
    {
        return $this->_theme;
    }
    
    protected function _prepareLayout()
    {
        $theme = $this->getTheme();
        $this->pageConfig->getTitle()->set($theme::wpGetDocumentTitle());
        return parent::_prepareLayout();
    }
    
    public function getWordPress()
    {
        return $this->_wp;
    }
    
    public function getQueryResult()
    {
        return $this->getWordPress()->getQueryResult();
    }
    
}
