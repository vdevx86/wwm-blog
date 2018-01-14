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

namespace Wwm\Blog\Cms\WordPress\Theme\Install\Bootstrap;

use Wwm\Blog\Cms\WordPress\Bootstrap\FileDataProviderInterface;

class File extends \Wwm\Blog\Cms\WordPress\Bootstrap\File
{
    
    protected $destination;
    
    public function __construct(
        \Wwm\Blog\Cms\WordPress\EntryPoint $entryPoint,
        \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory,
        \Zend\Filter\Compress\Gz $gz,
        \Wwm\Blog\Magento\Framework\Encryption\Encryptor $encryptor,
        $hash,
        $path,
        $source,
        $destination,
        FileDataProviderInterface $dataProvider = null
    ) {
        parent::__construct(
            $entryPoint,
            $readFactory,
            $gz,
            $encryptor,
            $hash,
            $path,
            $source,
            $dataProvider
        );
        $this->destination = $destination;
    }
    
    public function getDestination()
    {
        return $this->destination;
    }
    
}
