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

namespace Wwm\Blog\Cms\WordPress\Theme\LayerItem;

class Pluggable extends AbstractLayerItem
{
    
    protected $hookStorageGroup;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\Registry $registry,
        \Wwm\Blog\Cms\WordPress\EntryPoint $entryPoint,
        \Wwm\Blog\Cms\WordPress\Theme\Options $themeOptions,
        \Wwm\Blog\Cms\WordPress\Theme\Template\Parameters $templateParameters,
        \Magento\Framework\DataObject $templateParametersData,
        \Wwm\Blog\Cms\WordPress\Theme\Hook\Storage\Group $hookStorageGroup
    ) {
        parent::__construct(
            $context,
            $storeManager,
            $registry,
            $entryPoint,
            $themeOptions,
            $templateParameters,
            $templateParametersData
        );
        $this->hookStorageGroup = $hookStorageGroup;
    }
    
    public function getHookStorageGroup()
    {
        return $this->hookStorageGroup;
    }
    
    public function getDefaultAvatar()
    {
        return $this->entryPoint->getAvatar(
            $this->entryPoint->getTheAuthorMeta('user_email'),
            80,
            null,
            $this->entryPoint->getTheAuthor()
        );
    }
    
    public function theDefaultAvatar()
    {
        echo $this->entryPoint->getDefaultAvatar();
    }
    
}
