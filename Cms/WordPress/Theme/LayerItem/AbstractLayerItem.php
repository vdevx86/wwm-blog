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

namespace Wwm\Blog\Cms\WordPress\Theme\LayerItem;

abstract class AbstractLayerItem
{
    
    protected $context;
    protected $registry;
    protected $entryPoint;
    protected $themeOptions;
    protected $templateParameters;
    protected $templateParametersData;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Wwm\Blog\Cms\WordPress\EntryPoint $entryPoint,
        \Wwm\Blog\Cms\WordPress\Theme\Options $themeOptions,
        \Wwm\Blog\Cms\WordPress\Theme\Template\Parameters $templateParameters,
        \Magento\Framework\DataObject $templateParametersData
    ) {
        $this->context = $context;
        $this->registry = $registry;
        $this->entryPoint = $entryPoint;
        $this->themeOptions = $themeOptions;
        $this->templateParameters = $templateParameters;
        $this->templateParametersData = $templateParametersData;
    }
    
    public function getContext()
    {
        return $this->context;
    }
    
    public function getRegistry()
    {
        return $this->registry;
    }
    
}
