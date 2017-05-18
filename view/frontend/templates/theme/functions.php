<?php

if (is_admin()) {
    
    $requirePath = explode(DIRECTORY_SEPARATOR, __DIR__);
    array_splice($requirePath, -4);
    $requirePath = implode(DIRECTORY_SEPARATOR, $requirePath);
    
    require_once $requirePath . DIRECTORY_SEPARATOR . implode(
        DIRECTORY_SEPARATOR,
        ['Cms', 'WordPress', 'Theme', 'Wwm']
    ) . '.php';
    
    global $theme;
    $theme = new Wwm;
    $theme->enableAdminGlobalFilters();
    
} else {
    
    function checkCompatibility()
    {
        if (!defined('WWM_LOADED')) {
            wp_die(__('This theme is not designed to run separately without Magento 2 environment'));
        }
        if (!WWM_LOADED) {
            wp_die(__('WordPress unable to run inside Magento 2 environment because of incomplete initialization'));
        }
    }
    
}
