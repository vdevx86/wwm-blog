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

namespace Wwm\Blog\Cms\WordPress\Theme;

abstract class AbstractHook implements HookInterface
{
    
    protected $context;
    protected $registry;
    protected $entryPoint;
    protected $themeOptions;
    protected $templateParameters;
    protected $templateParametersData;
    protected $name;
    protected $priority;
    protected $argsCount;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Wwm\Blog\Cms\WordPress\EntryPoint $entryPoint,
        \Wwm\Blog\Cms\WordPress\Theme\Options $themeOptions,
        \Wwm\Blog\Cms\WordPress\Theme\Template\Parameters $templateParameters,
        \Magento\Framework\DataObject $templateParametersData,
        $name,
        $priority = self::DEFAULT_PRIORITY,
        $argsCount = self::DEFAULT_ARGS_COUNT
    ) {
        $this->context = $context;
        $this->registry = $registry;
        $this->entryPoint = $entryPoint;
        $this->themeOptions = $themeOptions;
        $this->templateParameters = $templateParameters;
        $this->templateParametersData = $templateParametersData;
        $this->name = $name;
        $this->priority = $priority;
        $this->argsCount = $argsCount;
    }
    
    public function getContext()
    {
        return $this->context;
    }
    
    public function getRegistry()
    {
        return $this->registry;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getPriority()
    {
        return $this->priority;
    }
    
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }
    
    public function getArgsCount()
    {
        return $this->argsCount;
    }
    
}
