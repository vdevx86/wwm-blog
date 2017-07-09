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
    
    const STATE_INIT = 0;
    const STATE_PROGRESS = 1;
    const STATE_SUCCESS = 2;
    
    protected $context;
    protected $config;
    protected $fileSystem;
    protected $patch;
    protected $composerFs;
    protected $themeLocator;
    
    protected $bootstrap = false;
    protected $state = self::STATE_INIT;
    
    protected $theme = null;
    protected $query = null;
    protected $type = Router::LT_DEFAULT;
    protected $result = null;
    
    protected $filterInit;
    protected $themeInit;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Wwm\Blog\Helper\Config $config,
        WordPress\FileSystem $fileSystem,
        WordPress\FileSystem\File\Patch $patch,
        \Composer\Util\Filesystem $composerFs,
        \Wwm\Blog\Cms\WordPress\FileSystem\Theme\Locator $themeLocator,
        $filterInit,
        $themeInit
    ) {
        $this->context = $context;
        $this->config = $config;
        $this->fileSystem = $fileSystem;
        $this->patch = $patch;
        $this->composerFs = $composerFs;
        $this->themeLocator = $themeLocator;
        $this->filterInit = $filterInit;
        $this->themeInit = $themeInit;
    }
    
    public function checkInitState()
    {
        if ($this->state != self::STATE_INIT) {
            throw new \LogicException('Method availability: only before initialization');
        }
        return $this;
    }
    
    public function checkProgressState()
    {
        if ($this->state != self::STATE_PROGRESS) {
            throw new \LogicException('Method availability: only during initialization');
        }
        return $this;
    }
    
    public function checkSuccessState()
    {
        if ($this->state != self::STATE_SUCCESS) {
            throw new \LogicException('Method availability: only after initialization');
        }
        return $this;
    }
    
    public function setBootstrap($bootstrap)
    {
        $this->checkInitState();
        $this->bootstrap = $bootstrap;
        return $this;
    }
    
    public function getBootstrap()
    {
        return $this->bootstrap;
    }
    
    public function getState()
    {
        return $this->state;
    }
    
    public function getTheme()
    {
        return $this->theme;
    }
    
    public function setQuery($query)
    {
        $this->checkInitState();
        $this->query = $query;
        return $this;
    }
    
    public function getQuery()
    {
        return $this->query;
    }
    
    public function setType($type)
    {
        $this->checkInitState();
        $this->type = $type;
        return $this;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function getQueryResult()
    {
        return $this->result;
    }
    
    public function loadTheme()
    {
        
        $this->checkProgressState();
        
        global $theme;
        $theme = $this->theme = $this->context->getObjectManager()
            ->create(\Wwm\Blog\Cms\WordPress\ThemeInterface::class);
        $theme->setHomeURL($theme->homeUrl())
            ->setHomeURLNew($this->config->getBaseUrlFrontend() . $this->config->getRouteName());
        
        if ($this->type == Router::LT_LOGIN) {
            $theme->enableScriptFilters();
        } else {
            define('WP_USE_THEMES', true);
            $theme->enableGlobalFilters();
        }
        
        return $this;
        
    }
    
    public function initSuperglobals()
    {
        
        $this->checkProgressState();
        
        $installationPath = DIRECTORY_SEPARATOR . $this->config->getInstallationPath() . DIRECTORY_SEPARATOR;
        $_SERVER['REQUEST_URI'] = $installationPath;
        
        if ($this->query) {
            $_SERVER['REQUEST_URI'] .= $this->query;
            $_SERVER['QUERY_STRING'] = parse_url($this->query, PHP_URL_QUERY);
            parse_str($_SERVER['QUERY_STRING'], $_GET);
        } else {
            $_SERVER['QUERY_STRING'] = '';
            $_GET = [];
        }
        
        $_SERVER['REQUEST_METHOD'] = [HTTPClient::GET, HTTPClient::POST][$this->type];
        
        $fileSystem = $this->fileSystem;
        $fileName = [$fileSystem::FN_INDEX, $fileSystem::FN_LOGIN][$this->type] . $fileSystem::FN_EXT;
        
        $_SERVER['SCRIPT_FILENAME'] = $fileSystem->getInstallationPath() . $fileName;
        $_SERVER['SCRIPT_NAME'] = DIRECTORY_SEPARATOR . $fileName;
        $_SERVER['PHP_SELF'] .= $installationPath . $fileName;
        
        unset($_SERVER['REDIRECT_URL'], $_SERVER['REDIRECT_QUERY_STRING']);
        return $this;
        
    }
    
    public function load()
    {
        
        $this->checkInitState();
        $this->state = self::STATE_PROGRESS;
        
        $patch = $this->patch;
        if ($file = $patch->getPatchedFile($patch::PT_CONFIG)) {
            $fileSystem = $this->fileSystem->load();
            define('ABSPATH', $fileSystem->getInstallationPath());
            eval($file);
            if ($file = $patch->getPatchedFile($patch::PT_TRANSLATIONS)) {
                eval($file);
                if ($file = $patch->getPatchedFile($patch::PT_SETTINGS)) {
                    
                    list($server, $get, $post) = [$_SERVER, $_GET, $_POST];
                    $this->initSuperglobals();
                    
                    require_once ABSPATH . $fileSystem::DIR_INCLUDES . DIRECTORY_SEPARATOR . $fileSystem::FN_PLUGIN . $fileSystem::FN_EXT;
                    add_filter($this->filterInit, [$this, $this->themeInit], 10, 0);
                    eval($file);
                    remove_filter($this->filterInit, [$this, $this->themeInit]);
                    
                    if ($this->type == Router::LT_LOGIN) {
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
                    $this->state = self::STATE_SUCCESS;
                    
                }
            }
        }
        
        if ($this->state != self::STATE_SUCCESS) {
            throw new InitException(__('Could not initialize WordPress environment'));
        }
        
        return $this;
        
    }
    
    public function installTheme()
    {
        
        $this->checkSuccessState();
        
        $to = get_theme_root() . DIRECTORY_SEPARATOR . self::THEME;
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
