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
use Magento\Framework\Exception\State\InitException;

final class WordPress
{
    
    const THEME = 'wwm';
    
    protected $context;
    protected $config;
    protected $fileSystem;
    protected $patch;
    protected $composerFs;
    protected $themeLocator;
    
    protected $filterInit;
    
    protected $bootstrap = false;
    protected $status = false;
    
    protected $theme = null;
    protected $result = null;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Wwm\Blog\Helper\Config $config,
        WordPress\FileSystem $fileSystem,
        WordPress\FileSystem\File\Patch $patch,
        \Composer\Util\Filesystem $composerFs,
        \Wwm\Blog\Cms\WordPress\FileSystem\Theme\Locator $themeLocator,
        $filterInit
    ) {
        $this->context = $context;
        $this->config = $config;
        $this->fileSystem = $fileSystem;
        $this->patch = $patch;
        $this->composerFs = $composerFs;
        $this->themeLocator = $themeLocator;
        $this->filterInit = $filterInit;
    }
    
    public function setBootstrap($bootstrap) { $this->bootstrap = $bootstrap; return $this; }
    public function getBootstrap() { return $this->bootstrap; }
    
    public function getStatus() { return $this->status; }
    
    public function getTheme() { return $this->theme; }
    public function getQueryResult() { return $this->result; }
    
    public function load($query = false, $type = Router::LT_DEFAULT)
    {
        
        $patch =& $this->patch;
        if ($file = $patch->getPatchedFile($patch::PT_CONFIG)) {
            $fileSystem = $this->fileSystem->load();
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
                        
                        global $theme;
                        $theme = $this->theme = $this->context->getObjectManager()
                            ->create(\Wwm\Blog\Cms\WordPress\ThemeInterface::class);
                        $theme->setHomeURL($theme->homeUrl())
                            ->setHomeURLNew($this->config->getBaseUrlFrontend() . $this->config->getRouteName());
                        
                        if ($type == Router::LT_LOGIN) {
                            $theme->enableScriptFilters();
                        } else {
                            define('WP_USE_THEMES', true);
                            $theme->enableGlobalFilters();
                        }
                        
                    };
                    
                    require_once ABSPATH . $fileSystem::DIR_INCLUDES . DIRECTORY_SEPARATOR .
                        $fileSystem::FN_PLUGIN . $fileSystem::FN_EXT;
                    
                    add_filter($this->filterInit, $buildClassTheme, 10, 0);
                    eval($file);
                    remove_filter($this->filterInit, $buildClassTheme);
                    unset($buildClassTheme);
                    
                    if ($type == Router::LT_LOGIN) {
                        if ($file = $patch->getPatchedFile($patch::PT_LOGIN)) {
                            eval($file);
                        }
                    } else {
                        
                        ob_start();
                        wp();
                        
                        if (!$this->getBootstrap()) {
                            $this->theme->enableLateGlobalFilters()->includeTemplateLoader();
                            $this->result = ob_get_contents();
                        }
                        
                        ob_end_clean();
                        
                    }
                    
                    list($_SERVER, $_GET, $_POST) = [$server, $get, $post];
                    $this->status = true;
                    
                }
            }
        }
        
        if (!$this->status) {
            throw new InitException(__('Could not initialize WordPress environment'));
        }
        
        return $this;
        
    }
    
    public function installTheme()
    {
        
        if (!$this->status) {
            throw new InitException(__('WordPress environment not initialized'));
        }
        
        $to = get_theme_root() . DIRECTORY_SEPARATOR . static::THEME;
        if ($this->composerFs->isSymlinkedDirectory($to)) {
            $this->composerFs->unlink($to);
        }
        
        $from = $this->themeLocator->getLocation();
        if (!$this->composerFs->relativeSymlink($from, $to)) {
            throw new FileSystemException(__(
                'Could not install WordPress theme. Error creating symlink: %1 => %2',
                $from,
                $to
            ));
        }
        
        return $this;
        
    }
    
}
