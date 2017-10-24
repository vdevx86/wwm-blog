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
    
    protected $resultJsonFactory;
    protected $wp;
    protected $themeInstall;
    protected $bootstrapMode;
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Wwm\Blog\Cms\WordPress $wp,
        \Wwm\Blog\Cms\WordPress\FileSystem\Theme\Install $themeInstall,
        \Wwm\Blog\Cms\WordPress\Bootstrap\Mode $bootstrapMode
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->wp = $wp;
        $this->themeInstall = $themeInstall;
        $this->bootstrapMode = $bootstrapMode;
        parent::__construct($context);
    }
    
    public function execute()
    {
        
        $result = false;
        
        try {
            
            $this->bootstrapMode->enable();
            $this->wp->load();
            $this->themeInstall->install();
            
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
