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

class Index extends \Magento\Framework\App\Action\Action
{
    
    protected $context;
    protected $forwardFactory;
    protected $wp;
    protected $resultPageFactory;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\ForwardFactory $forwardFactory,
        \Wwm\Blog\Cms\WordPress $wp,
        \Wwm\Blog\Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->context = $context;
        $this->forwardFactory = $forwardFactory;
        $this->wp = $wp;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
    
    public function execute()
    {
        
        $result = false;
        
        try {
            $this->wp->setQuery($this->getRequest()->getParam(Router::ROUTER_PARAMETER, false))->load();
            $result = $this->resultPageFactory->create()->addDefaultHandle();
        } catch (\Exception $e) {
            $this->context->getMessageManager()->addError($e->getMessage());
        }
        
        if (!$result) {
            $this->getRequest()->setRequestUri('')->setDispatched(false);
            $result = $this->forwardFactory->create()->forward('noroute');
        }
        
        return $result;
        
    }
    
}
