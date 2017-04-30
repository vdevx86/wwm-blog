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
    
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Wwm\Blog\Cms\WordPress $wp,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->wp = $wp;
    }
    
    public function getQueryResult()
    {
        return $this->wp->getQueryResult();
    }
    
}
