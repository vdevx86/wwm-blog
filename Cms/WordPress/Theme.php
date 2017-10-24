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

class Theme extends Renderer implements ThemeInterface
{
    
    protected $objectManager;
    protected $context;
    protected $registry;
    protected $entryPoint;
    protected $options;
    protected $initState = false;
    
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        EntryPoint $entryPoint,
        Theme\Options $options,
        FileSystem $fileSystem
    ) {
        $this->objectManager = $objectManager;
        $this->context = $context;
        $this->registry = $registry;
        $this->entryPoint = $entryPoint;
        $this->options = $options;
        parent::__construct($objectManager, $fileSystem);
    }
    
    public function getContext()
    {
        return $this->context;
    }
    
    public function getRegistry()
    {
        return $this->registry;
    }
    
    public function getOptions()
    {
        return $this->options;
    }
    
    public function getInitState()
    {
        return $this->initState;
    }
    
    public function setInitState()
    {
        $this->initState = true;
        return $this;
    }
    
    public function __call($name, $args)
    {
        return $this->entryPoint->{$name}(...$args);
    }
    
}
