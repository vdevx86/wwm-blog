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

use Wwm\Blog\Cms\WordPress\ThemeInterface;

class Template extends AbstractLayerItem
{
    
    public function getTemplateParameters()
    {
        return $this->templateParameters->get();
    }
    
    public function locateTemplates(array $templateNames, $load = true, $requireOnce = false)
    {
        foreach ($templateNames as &$templateName) {
            array_unshift($templateName, ThemeInterface::SUBTEMPLATE_PREFIX);
            $templateName = implode(DIRECTORY_SEPARATOR, $templateName) . ThemeInterface::FN_EXT;
        }
        return locate_template($templateNames, $load, $requireOnce);
    }
    
    public function locateTemplate(array $templateName, $load = true, $requireOnce = false)
    {
        return $this->entryPoint->locateTemplates([$templateName], $load, $requireOnce);
    }
    
    public function renderTemplate(array $templateName, $load = true, $requireOnce = false)
    {
        return $this->entryPoint->getContents(function($templateName, $load, $requireOnce) {
            $this->entryPoint->locateTemplates([$templateName], $load, $requireOnce);
        }, [$templateName, $load, $requireOnce]);
    }
    
    public function getNavigation()
    {
        $this->entryPoint->locateTemplate(['navigation']);
    }
    
    public function thePrimaryButton()
    {
        $this->entryPoint->locateTemplate(['button', 'primary']);
    }
    
    public function theDefaultEmptyMessage()
    {
        $this->entryPoint->locateTemplate(['message', 'empty']);
    }
    
}
