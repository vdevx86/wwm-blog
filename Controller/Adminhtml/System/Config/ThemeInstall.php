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

namespace Wwm\Blog\Controller\Adminhtml\System\Config;

class ThemeInstall extends \Magento\Backend\App\Action
{
    
    protected $context;
    protected $resultJsonFactory;
    protected $wp;
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Wwm\Blog\Cms\WordPress $wp
    ) {
        $this->context = $context;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->wp = $wp;
        parent::__construct($context);
    }
    
    public function execute()
    {
        
        $result = false;
        
        try {
            
            $this->wp->setBootstrap(true)
                ->load()
                ->installTheme();
            
            $result = __('Theme installed successfully');
            
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }
        
        if (!$result) {
            $result = __('Unknown error');
        }
        
        return $this->resultJsonFactory->create()->setData([
            'message' => $result
        ]);
        
    }
    
}
