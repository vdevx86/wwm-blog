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

namespace Wwm\Blog\Block\System\Config\Form\Field\Button;

class ThemeInstall extends \Magento\Config\Block\System\Config\Form\Field
{
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('system/config/form/field/button/themeinstall.phtml');
        }
        return $this;
    }
    
    public function getButtonHtml()
    {
        return $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData([
            'id' => 'wwm_blog_theme_install',
            'label' => __('Install Theme'),
            'onclick' => 'javascript:installWordPressTheme();return!1',
        ])->toHtml();
    }
    
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }
    
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return $this->_toHtml();
    }
    
    public function getAjaxUrl()
    {
        return $this->_urlBuilder->getUrl('wwm_blog/system_config/themeinstall');
    }
    
}
