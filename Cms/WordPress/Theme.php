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

// @codingStandardsIgnoreFile

namespace Wwm\Blog\Cms\WordPress;

class Theme implements ThemeInterface
{
    
    const NAME = 'wwm';
    
    protected $context;
    protected $registry;
    protected $entryPoint;
    protected $options;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        EntryPoint $entryPoint,
        Theme\Options $options
    ) {
        $this->context = $context;
        $this->registry = $registry;
        $this->entryPoint = $entryPoint;
        $this->options = $options;
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
    
    public function includeTemplateLoader()
    {
        require_once ABSPATH . WPINC . DIRECTORY_SEPARATOR . FileSystem::FN_TPLDR . FileSystem::FN_EXT;
        return $this;
    }
    
    public function __call($name, array $args)
    {
        return $this->entryPoint->{$name}(...$args);
    }
    
}
