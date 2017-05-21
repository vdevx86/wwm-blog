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

namespace Wwm\Blog\Cms;

use Wwm\Blog\Controller\Router;
use Wwm\Blog\Cms\WordPress\Theme;

use Magento\Framework\HTTP\ZendClient as HTTPClient;
use Magento\Framework\Exception\FileSystemException;

final class WordPress
{
    
    const FLAG_STATUS = 'WWM_LOADED';
    const FILTER_INIT = 'muplugins_loaded';
    
    protected $context;
    protected $config;
    protected $fileSystem;
    protected $patch;
    protected $composerFs;
    
    protected $theme = null;
    protected $result = null;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Wwm\Blog\Helper\Config $config,
        WordPress\FileSystem $fileSystem,
        WordPress\FileSystem\File\Patch $patch,
        \Composer\Util\Filesystem $composerFs
    ) {
        $this->context = $context;
        $this->config = $config;
        $this->fileSystem = $fileSystem;
        $this->patch = $patch;
        $this->composerFs = $composerFs;
    }
    
    public function getTheme() { return $this->theme; }
    public function getQueryResult() { return $this->result; }
    
    public function load($query = false, $type = Router::LT_DEFAULT)
    {
        
        $result = false;
        
        if (!$this->isLoaded()) {
            
            $fileSystem = $this->fileSystem->load();
            $patch =& $this->patch;
            
            if ($file = $patch->getPatchedFile($patch::PT_CONFIG)) {
                define('ABSPATH', $fileSystem->getInstallationPath());
                eval($file);
                if ($file = $patch->getPatchedFile($patch::PT_TRANSLATIONS)) {
                    eval($file);
                    if ($file = $patch->getPatchedFile($patch::PT_SETTINGS)) {
                        
                        list($server, $get, $post) = [$_SERVER, $_GET, $_POST];
                        
                        $requestMethod =& $_SERVER['REQUEST_METHOD'];
                        
                        $scriptFilename =& $_SERVER['SCRIPT_FILENAME'];
                        $scriptFilename = $fileSystem->getInstallationPath();
                        
                        $scriptName =& $_SERVER['SCRIPT_NAME'];
                        $scriptName = DIRECTORY_SEPARATOR;
                        
                        $requestURI =& $_SERVER['REQUEST_URI'];
                        $requestURI = DIRECTORY_SEPARATOR . $this->config->getInstallationPath() . DIRECTORY_SEPARATOR;
                        
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
                        
                        if ($type == Router::LT_LOGIN) {
                            $requestMethod = HTTPClient::POST;
                            $scriptFilename .= $fileSystem::FN_LOGIN;
                            $scriptName .= $fileSystem::FN_LOGIN;
                            $phpSelf .= $fileSystem::FN_LOGIN;
                        } else {
                            $requestMethod = HTTPClient::GET;
                            $scriptFilename .= $fileSystem::FN_INDEX;
                            $scriptName .= $fileSystem::FN_INDEX;
                            $phpSelf .= $fileSystem::FN_INDEX;
                        }
                        
                        $scriptFilename .= $fileSystem::FN_EXT;
                        $scriptName .= $fileSystem::FN_EXT;
                        $phpSelf .= $fileSystem::FN_EXT;
                        
                        unset($query, $_SERVER['REDIRECT_URL'], $_SERVER['REDIRECT_QUERY_STRING']);
                        
                        $buildClassTheme = function () use ($type) {
                            
                            $to = get_theme_root() . DIRECTORY_SEPARATOR . Theme::NAME;
                            if (!$this->composerFs->isSymlinkedDirectory($to)) {
                                $from = $this->fileSystem->getThemeDirectory();
                                if (!$this->composerFs->relativeSymlink($from, $to)) {
                                    throw new FileSystemException(__(
                                        'Could not install WordPress theme. Error creating symlink: %1 => %2',
                                        $from,
                                        $to
                                    ));
                                }
                            }
                            
                            global $theme;
                            $theme = $this->theme = $this->context->getObjectManager()->create(Theme::class);
                            $theme->setHomeURL($theme::homeUrl())->setHomeURLNew(
                                $this->config->getBaseUrlFrontend() . $this->config->getRouteName()
                            );
                            
                            if ($type == Router::LT_LOGIN) {
                                $theme->enableScriptFilters();
                            } else {
                                define('WP_USE_THEMES', true);
                                $theme->enableGlobalFilters();
                            }
                            
                        };
                        
                        require_once ABSPATH . $fileSystem::DIR_INCLUDES . DIRECTORY_SEPARATOR .
                            $fileSystem::FN_PLUGIN . $fileSystem::FN_EXT;
                        
                        add_filter(static::FILTER_INIT, $buildClassTheme, 10, 0);
                        eval($file);
                        remove_filter(static::FILTER_INIT, $buildClassTheme);
                        unset($buildClassTheme);
                        
                        if ($type == Router::LT_LOGIN) {
                            if ($file = $patch->getPatchedFile($patch::PT_LOGIN)) {
                                eval($file);
                            }
                        } else {
                            
                            define(static::FLAG_STATUS, true);
                            
                            ob_start();
                            wp();
                            
                            $this->theme->enableLateGlobalFilters()->includeTemplateLoader();
                            $this->result = ob_get_contents();
                            
                            ob_end_clean();
                            
                        }
                        
                        list($_SERVER, $_GET, $_POST) = [$server, $get, $post];
                        $result = $this;
                        
                    }
                }
            }
            
            if (!$result) {
                define(static::FLAG_STATUS, false);
            }
            
        }
        
        return $result;
        
    }
    
    public static function isLoaded()
    {
        return defined(static::FLAG_STATUS);
    }
    
    public static function isLoadedSuccessfully()
    {
        return static::isLoaded() && constant(static::FLAG_STATUS);
    }
    
}
