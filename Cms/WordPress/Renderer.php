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

class Renderer extends \Magento\Framework\View\TemplateEngine\Php
{
    
    protected $objectManager;
    protected $fileSystem;
    protected $block;
    
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        FileSystemInterface $fileSystem
    ) {
        $this->objectManager = $objectManager;
        $this->fileSystem = $fileSystem;
        $this->_construct();
        parent::__construct($objectManager);
    }
    
    protected function _construct()
    {
        $this->initBlock();
    }
    
    public function initBlock($className = Renderer\Block::class)
    {
        $this->block = $this->objectManager->get($className);
    }
    
    public function renderEntity($fileName)
    {
        
        $installationPath = $this->fileSystem->getDirectoryRead()
            ->getAbsolutePath();
        
        return $this->render(
            $this->block,
            $installationPath . $fileName . FileSystemInterface::FN_EXT
        );
        
    }
    
    public function __call($name, $args)
    {
    }
    
    public function __isset($name)
    {
    }
    
    public function __get($name)
    {
    }
    
}
