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

namespace Wwm\Blog\Cms\WordPress\Bootstrap\File\Main;

use Wwm\Blog\Cms\WordPress\FileSystemInterface;
use Wwm\Blog\Cms\WordPress\Bootstrap\FileDataProviderInterface;
use Wwm\Blog\Cms\WordPress\FileSystem\File\PatcherFactory;

class MainFileDataProvider implements FileDataProviderInterface
{
    
    protected $data;
    protected $mathRandom;
    protected $config;
    protected $loadType;
    protected $fileSystem;
    protected $patcherFactory;
    protected $query;
    
    public function __construct(
        \Magento\Framework\DataObject $data,
        \Magento\Framework\Math\Random $mathRandom,
        \Wwm\Blog\Cms\WordPress\ConfigInterface $config,
        \Wwm\Blog\Cms\WordPress\LoadType $loadType,
        FileSystemInterface $fileSystem,
        PatcherFactory $patcherFactory,
        \Wwm\Blog\Cms\WordPress\Query $query
    ) {
        $this->data = $data;
        $this->mathRandom = $mathRandom;
        $this->config = $config;
        $this->loadType = $loadType;
        $this->fileSystem = $fileSystem;
        $this->patcherFactory = $patcherFactory;
        $this->query = $query;
    }
    
    public function getData()
    {
        
        $patch = $this->patcherFactory->create();
        $installationPath = $this->fileSystem->getDirectoryRead()
            ->getAbsolutePath();
        
        $data = $this->data;
        $data->setUniqueId($this->mathRandom->getUniqueHash())
            ->setConfigInstallationPath($this->config->getInstallationPath())
            ->setQuery($this->query->get())
            ->setLoadType($this->loadType->getType())
            ->setFileSystemInstallationPath($installationPath)
            ->setFileName($this->loadType->toFileName())
            ->setUseThemes((int)!$this->loadType->getType())
            ->setFileConfig($patch->config())
            ->setFileTranslations($patch->translations())
            ->setFileSettings($patch->settings());
        
        $fileLogin = null;
        if ($this->loadType->isLogin()) {
            $fileLogin = $patch->login();
        }
        $data->setFileLogin($fileLogin);
        
        return $data->getData();
        
    }
    
}
