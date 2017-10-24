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

use Wwm\Blog\Cms\WordPress\Bootstrap\FileDataProviderInterface;

class MainFileDataProvider implements FileDataProviderInterface
{
    
    protected $data;
    protected $mathRandom;
    protected $config;
    protected $loadType;
    protected $fileSystem;
    protected $patch;
    protected $query;
    
    public function __construct(
        \Magento\Framework\DataObject $data,
        \Magento\Framework\Math\Random $mathRandom,
        \Wwm\Blog\Helper\Config $config,
        \Wwm\Blog\Cms\WordPress\LoadType $loadType,
        \Wwm\Blog\Cms\WordPress\FileSystem $fileSystem,
        \Wwm\Blog\Cms\WordPress\FileSystem\File\Php\Patch $patch,
        \Wwm\Blog\Cms\WordPress\Query $query
    ) {
        $this->data = $data;
        $this->mathRandom = $mathRandom;
        $this->config = $config;
        $this->loadType = $loadType;
        $this->fileSystem = $fileSystem;
        $this->patch = $patch;
        $this->query = $query;
    }
    
    public function getData()
    {
        
        $data = $this->data;
        $patch = $this->patch;
        
        $data->setUniqueId($this->mathRandom->getUniqueHash())
            ->setConfigInstallationPath($this->config->getInstallationPath())
            ->setQuery($this->query->get())
            ->setLoadType($this->loadType->getType())
            ->setFileSystemInstallationPath(
                $this->fileSystem->load()->getInstallationPath()
            )
            ->setFileName($this->loadType->toFileName())
            ->setUseThemes((int)!$this->loadType->getType())
            ->setFileConfig($patch->getFile($patch::PT_CONFIG))
            ->setFileTranslations($patch->getFile($patch::PT_TRANSLATIONS))
            ->setFileSettings($patch->getFile($patch::PT_SETTINGS));
        
        $fileLogin = null;
        if ($this->loadType->isLogin()) {
            $fileLogin = $patch->getFile($patch::PT_LOGIN);
        }
        $data->setFileLogin($fileLogin);
        
        return $data->getData();
        
    }
    
}
