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

if (is_admin()) {
    
    $bootstrap = function() {
        
        require_once implode(DIRECTORY_SEPARATOR, [dirname(ABSPATH), 'app', 'autoload.php']);
        
        $setupUmask = function() {
            $umaskFile = BP . DIRECTORY_SEPARATOR . 'magento_umask';
            $mask = file_exists($umaskFile) ? octdec(file_get_contents($umaskFile)) : 002;
            umask($mask);
        };
        
        $checkIISRewrites = function() {
            $key = 'ENABLE_IIS_REWRITES';
            if (empty($_SERVER[$key]) || ($_SERVER[$key] != 1)) {
                foreach ([
                    'HTTP_X_REWRITE_URL',
                    'HTTP_X_ORIGINAL_URL',
                    'IIS_WasUrlRewritten',
                    'UNENCODED_URL',
                    'ORIG_PATH_INFO'
                ] as $value) {
                    unset($_SERVER[$value]);
                }
            }
        };
        
        $setupProfiler = function() {
            if (
                    !empty($_SERVER['MAGE_PROFILER'])
                &&  isset($_SERVER['HTTP_ACCEPT'])
                &&  strpos($_SERVER['HTTP_ACCEPT'], 'text/html') !== false
            ) {
                \Magento\Framework\Profiler::applyConfig(
                    $_SERVER['MAGE_PROFILER'],
                    BP,
                    !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
                        && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'
                );
            }
        };
        
        $bootstrapTheme = function() {
            
            $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
            $objectManager = $bootstrap->getObjectManager();
            
            $state = $objectManager->get(\Magento\Framework\App\State::class);
            $configLoader = $objectManager->get(\Magento\Framework\ObjectManager\ConfigLoaderInterface::class);
            
            $areaCode = \Wwm\Blog\Cms\WordPress\ThemeInterface::AREA_CODE;
            $state->setAreaCode($areaCode);
            $objectManager->configure($configLoader->load($areaCode));
            
            global $theme;
            $theme = $objectManager->get(\Wwm\Blog\Cms\WordPress\ThemeInterface::class);
            $theme->enableAdminGlobalFilters();
            
        };
        
        $setupUmask();
        $checkIISRewrites();
        $setupProfiler();
        $bootstrapTheme();
        
    };
    
    $bootstrap();
    unset($bootstrap);
    
} else {
    
    function checkCompatibility()
    {
        if (!defined('VENDOR_PATH')) {
            wp_die(__('This theme is not designed to run separately without Magento 2 environment'));
        }
    }
    
}
