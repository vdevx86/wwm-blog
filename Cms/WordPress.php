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

namespace Wwm\Blog\Cms;

use Wwm\Blog\Cms\WordPress\Theme;
use Wwm\Blog\Cms\WordPress\FileSystem;
use Wwm\Blog\Cms\WordPress\FileSystem\File\Patch;
use Wwm\Blog\Cms\WordPress\FileSystem\Directory;
use Wwm\Blog\Controller\Router;

class WordPress
{
    
    const FLAG_STATUS = 'WWM_LOADED';
    const WP_HOMEURL = 'WWM_URL_HOME';
    const WP_HOMEURL_NEW = 'WWM_URL_HOME_NEW';
    
    // Supported request methods
    const RM_GET = 'GET';
    const RM_POST = 'POST';
    
    const FILTER_INIT = 'muplugins_loaded';
    
    const MSG_NOT_COMPATIBLE = 'Selected WordPress theme is not compatible with Magento 2';
    
    protected $_context;
    protected $_objectManager;
    protected $_config;
    protected $_fileSystem;
    protected $_patch;
    
    protected $_theme = null;
    protected $_result = null;
    protected $_routerType = null;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Wwm\Blog\Helper\Config $config,
        FileSystem $fileSystem,
        Patch $patch
    ) {
        $this->_context = $context;
        $this->_objectManager = $context->getObjectManager();
        $this->_config = $config;
        $this->_fileSystem = $fileSystem;
        $this->_patch = $patch;
    }
    
    public function getContext()
    {
        return $this->_context;
    }
    
    public function getObjectManager()
    {
        return $this->_objectManager;
    }
    
    public function getConfig()
    {
        return $this->_config;
    }
    
    public function getComponentRegistry()
    {
        return $this->_componentRegistry;
    }
    
    public function getFileSystem()
    {
        return $this->_fileSystem->load();
    }
    
    public function getPatch()
    {
        return $this->_patch;
    }
    
    public function getTheme()
    {
        return $this->_theme;
    }
    
    public function getQueryResult()
    {
        return $this->_result;
    }
    
    public function getRouterType()
    {
        return $this->_routerType;
    }
    
    public function setRouterType($routerType)
    {
        $this->_routerType = $routerType;
        return $this;
    }
    
    public function load(
        $query = false,
        $type = Router::LT_DEFAULT
    ) {
        
        if ($this->isLoaded()) {
            return $this;
        }
        
        $fileSystem = $this->getFileSystem();
        $patch = $this->getPatch();
        
        if ($file = $patch->getPatchedFile(Patch::PT_CONFIG)) {
            define('ABSPATH', $fileSystem->getInstallationPath());
            eval($file);
            if ($file = $patch->getPatchedFile(Patch::PT_TRANSLATIONS)) {
                eval($file);
                if ($file = $patch->getPatchedFile(Patch::PT_SETTINGS)) {
                    
                    list($server, $get, $post) = [$_SERVER, $_GET, $_POST];
                    
                    $requestMethod =& $_SERVER['REQUEST_METHOD'];
                    $scriptFilename =& $_SERVER['SCRIPT_FILENAME'];
                    $scriptFilename = $fileSystem->getInstallationPath();
                    $scriptName =& $_SERVER['SCRIPT_NAME'];
                    $scriptName = DIRECTORY_SEPARATOR;
                    $requestURI =& $_SERVER['REQUEST_URI'];
                    $requestURI = DIRECTORY_SEPARATOR . $this->getConfig()->getInstallationPath() . DIRECTORY_SEPARATOR;
                    $phpSelf =& $_SERVER['PHP_SELF'];
                    $phpSelf = $requestURI;
                    $queryString =& $_SERVER['QUERY_STRING'];
                    
                    if ($query) {
                        $requestURI .= $query;
                        $queryString = parse_url($query, PHP_URL_QUERY);
                        parse_str($queryString, $_GET);
                    } else {
                        $queryString = '';
                        $_GET = [];
                    }
                    
                    switch ($type) {
                        case Router::LT_LOGIN:
                            $requestMethod = static::RM_POST;
                            $scriptFilename .= FileSystem::FN_LOGIN;
                            $scriptName .= FileSystem::FN_LOGIN;
                            $phpSelf .= FileSystem::FN_LOGIN;
                            break;
                        default:
                            $requestMethod = static::RM_GET;
                            $scriptFilename .= FileSystem::FN_INDEX;
                            $scriptName .= FileSystem::FN_INDEX;
                            $phpSelf .= FileSystem::FN_INDEX;
                    }
                    
                    $scriptFilename .= FileSystem::FN_EXT;
                    $scriptName .= FileSystem::FN_EXT;
                    $phpSelf .= FileSystem::FN_EXT;
                    
                    unset($query, $_SERVER['REDIRECT_URL'], $_SERVER['REDIRECT_QUERY_STRING']);
                    
                    $buildClassTheme = function () use ($type) {
                        
                        $classFile = get_template_directory() . DIRECTORY_SEPARATOR . FileSystem::FN_CLASS . FileSystem::FN_EXT;
                        
                        if (!FileSystem::validateFile($classFile)) {
                            throw new \Exception(__(static::MSG_NOT_COMPATIBLE));
                        }
                        
                        require $classFile;
                        
                        if (!class_exists('WWMT')) {
                            throw new \Exception(__(static::MSG_NOT_COMPATIBLE));
                        }
                        
                        $config = $this->getConfig();
                        
                        global $theme;
                        $theme = $this->_theme = $this->getObjectManager()->create(Theme::class);
                        $theme->setHomeURL($theme::homeUrl())
                            ->setHomeURLNew($config->getBaseUrlFrontend() . $config->getRouteName());
                        
                        switch ($type) {
                            case Router::LT_LOGIN:
                                $theme->enableScriptFilters();
                                break;
                            default:
                                define('WP_USE_THEMES', true);
                                $theme->enableGlobalFilters();
                        }
                        
                    };
                    
                    require_once ABSPATH . FileSystem::DIR_INCLUDES . DIRECTORY_SEPARATOR . FileSystem::FN_PLUGIN . FileSystem::FN_EXT;
                    add_filter(static::FILTER_INIT, $buildClassTheme, 10, 0);
                    
                    eval($file);
                    
                    remove_filter(static::FILTER_INIT, $buildClassTheme);
                    unset($buildClassTheme);
                    
                    switch ($type) {
                        
                        case Router::LT_LOGIN:
                            
                            if ($file = $patch->getPatchedFile(Patch::PT_LOGIN)) {
                                eval($file);
                            }
                            
                            break;
                            
                        default:
                            
                            define(static::FLAG_STATUS, true);
                            
                            ob_start();
                            wp();
                            
                            $this->getTheme()
                                ->enableLateGlobalFilters()
                                ->includeTemplateLoader();
                            
                            $this->_result = ob_get_contents();
                            ob_end_clean();
                            
                    }
                    
                    list($_SERVER, $_GET, $_POST) = [$server, $get, $post];
                    return $this;
                    
                }
            }
        }
        
        define(static::FLAG_STATUS, false);
        return false;
        
    }
    
    public static function isLoaded()
    {
        return defined(static::FLAG_STATUS);
    }
    
    public static function isLoadedSuccessfully()
    {
        return static::isLoaded() && constant(static::FLAG_STATUS);
    }
    
    public static function checkLoadStatus()
    {
        if (!static::isLoadedSuccessfully()) {
            throw new Exception(__('WordPress environment not initialized'));
        }
    }
    
}
