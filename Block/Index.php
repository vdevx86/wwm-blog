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
    
    protected $wp;
    protected $entryPoint;
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Wwm\Blog\Cms\WordPress $wp,
        \Wwm\Blog\Cms\WordPress\EntryPoint $entryPoint,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->wp = $wp;
        $this->entryPoint = $entryPoint;
    }
    
    protected function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(
            $this->entryPoint->wpGetDocumentTitle()
        );
        return parent::_prepareLayout();
    }
    
    public function getResult()
    {
        return $this->wp->getResult();
    }
    
}
