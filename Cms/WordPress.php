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

final class WordPress implements CmsInterface
{
    
    protected $entryPoint;
    protected $bootstrap;
    protected $themeFactory;
    protected $fileSystem;
    protected $loadType;
    protected $bootstrapMode;
    
    protected $result = false;
    
    public function __construct(
        WordPress\EntryPoint $entryPoint,
        WordPress\Bootstrap $bootstrap,
        WordPress\ThemeFactory $themeFactory,
        WordPress\FileSystem $fileSystem,
        WordPress\LoadType $loadType,
        WordPress\Bootstrap\Mode $bootstrapMode
    ) {
        $this->entryPoint = $entryPoint;
        $this->bootstrap = $bootstrap;
        $this->themeFactory = $themeFactory;
        $this->fileSystem = $fileSystem;
        $this->loadType = $loadType;
        $this->bootstrapMode = $bootstrapMode;
    }
    
    public function load()
    {
        
        $fileSystem = $this->fileSystem->load();
        
        $this->bootstrap->essentials(true);
        $this->entryPoint->environmentSave();
        $this->bootstrap->main(true);
        
        if (
                $this->loadType->isDefault()
            &&  $this->bootstrapMode->disabled()
        ) {
            $this->result = $this->themeFactory->create()
                ->renderEntity(
                    WPINC .
                    DIRECTORY_SEPARATOR .
                    $fileSystem::FN_TPLDR
                );
        }
        
        $this->entryPoint->environmentRestore();
        return $this;
        
    }
    
    public function getResult()
    {
        return $this->result;
    }
    
}
