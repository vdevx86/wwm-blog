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

namespace Wwm\Blog\Magento\Framework\View\Page\Config;

class Renderer extends \Magento\Framework\View\Page\Config\Renderer
{
    
    public function renderTitle()
    {
        return false;
    }
    
    public function getMetadataTemplate($name)
    {
        
        if (strpos($name, 'og:') === 0) {
            return '<meta property="' . $name . '" content="%content"/>' . "\n";
        }
        
        switch ($name) {
            
            case 'charset':
                $metadataTemplate = '<meta charset="%content"/>' . "\n";
                break;
            
            case 'content_type':
                $metadataTemplate = '<meta http-equiv="Content-Type" content="%content"/>' . "\n";
                break;
            
            case 'x_ua_compatible':
                $metadataTemplate = '<meta http-equiv="X-UA-Compatible" content="%content"/>' . "\n";
                break;
            
            case 'description':
            case 'keywords':
            case 'robots':
            case 'media_type':
                $metadataTemplate = false;
                break;
            
            default:
                $metadataTemplate = '<meta name="%name" content="%content"/>' . "\n";
            
        }
        
        return $metadataTemplate;
        
    }
    
}
