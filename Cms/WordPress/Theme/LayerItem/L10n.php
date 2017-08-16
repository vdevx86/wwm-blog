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

namespace Wwm\Blog\Cms\WordPress\Theme\LayerItem;

use Wwm\Blog\Cms\WordPress\ThemeInterface;

class L10n extends AbstractLayerItem
{
    
    public function getTextDomain()
    {
        return $this->themeOptions->getTextDomain();
    }
    
    public function __($text)
    {
        $newText = __($text);
        if ($newText != $text) {
            return $newText;
        }
        return __($text, $this->entryPoint->getTextDomain());
    }
    
    public function _e($text)
    {
        echo $this->__($text);
    }
    
    public function _x($text, $context, $domain = null)
    {
        $newText = _x($text, $context, ThemeInterface::TEXT_DOMAIN_DEFAULT);
        if ($newText != $text) {
            return $newText;
        }
        if ($domain === null) {
            $domain = $this->entryPoint->getTextDomain();
        }
        return _x($text, $context, $domain);
    }
    
    public function _ex($text, $context, $domain = null)
    {
        if ($domain === null) {
            $domain = $this->entryPoint->getTextDomain();
        }
        echo $this->_x($text, $context, $domain);
    }
    
    public function _n($single, $plural, $number, $domain = null)
    {
        $newText = _n($single, $plural, $number, ThemeInterface::TEXT_DOMAIN_DEFAULT);
        if ($newText != $text) {
            return $newText;
        }
        if ($domain === null) {
            $domain = $this->entryPoint->getTextDomain();
        }
        return _n($single, $plural, $number, $domain);
    }
    
    public function _nx($single, $plural, $number, $context, $domain = null)
    {
        $newText = _nx($single, $plural, $number, $context, ThemeInterface::TEXT_DOMAIN_DEFAULT);
        if ($newText != $text) {
            return $newText;
        }
        if ($domain === null) {
            $domain = $this->entryPoint->getTextDomain();
        }
        return _nx($single, $plural, $number, $context, $domain);
    }
    
    public function loadTextDomainJustInTime($domain)
    {
        return _load_textdomain_just_in_time($domain);
    }
    
}
