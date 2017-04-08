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

namespace Wwm\Blog\Controller\WordPress;

use Wwm\Blog\Controller\Router;

abstract class AbstractAction extends \Magento\Framework\App\Action\Action
{
    
    protected $_context;
    protected $_forwardFactory;
    protected $_wp;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\ForwardFactory $forwardFactory,
        \Wwm\Blog\Cms\WordPress $wp
    ) {
        parent::__construct($context);
        $this->_context = $context;
        $this->_forwardFactory = $forwardFactory;
        $this->_wp = $wp;
    }
    
    public function getContext()
    {
        return $this->_context;
    }
    
    public function getView()
    {
        return $this->_view;
    }
    
    public function getForwardFactory()
    {
        return $this->_forwardFactory;
    }
    
    public function getWordPress()
    {
        return $this->_wp;
    }
    
    public function getRouterParameter()
    {
        return $this->getRequest()->getParam(Router::ROUTER_PARAMETER, false);
    }
    
    protected function _forwardNoRoute($message)
    {
        
        $this->getContext()
            ->getMessageManager()
            ->addError($message);
        
        return $this->getForwardFactory()
            ->create()
            ->forward('noroute');
        
    }
    
}
