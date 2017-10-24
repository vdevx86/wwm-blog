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

namespace Wwm\Blog\Cms\WordPress\Bootstrap;

class File extends \Zend\Serializer\Adapter\PhpCode implements FileInterface
{
    
    protected $readFactory;
    protected $gz;
    protected $encryptor;
    protected $hash;
    protected $path;
    protected $source;
    protected $dataProvider;
    
    protected $result = false;
    protected $contents = null;
    
    public function __construct(
        \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory,
        \Zend\Filter\Compress\Gz $gz,
        \Magento\Framework\Encryption\Encryptor $encryptor,
        $hash,
        $path,
        $source,
        FileDataProviderInterface $dataProvider = null
    ) {
        $this->readFactory = $readFactory;
        $this->gz = $gz->setMode(self::ENC_MODE);
        $this->encryptor = $encryptor;
        $this->hash = $hash;
        $this->path = $path;
        $this->source = $source;
        $this->dataProvider = $dataProvider;
    }
    
    public function unserialize($code)
    {
        throw new \LogicException('Method is unavailable');
    }
    
    private function invoke($code)
    {
        return parent::unserialize($code);
    }
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function getHash()
    {
        return $this->hash;
    }
    
    public function getSource()
    {
        return $this->source;
    }
    
    public function getResult()
    {
        return $this->result;
    }
    
    public function getContents()
    {
        return $this->contents;
    }
    
    public function read($invoke = false)
    {
        
        $directoryRead = $this->readFactory->create($this->path);
        
        $contents = $directoryRead->readFile($this->source);
        $contents = implode(self::ENC_DMTR, [
            self::ENC_PRM1,
            self::ENC_PRM2,
            $this->hash,
            $contents
        ]);
        
        $contents = $this->encryptor->decrypt($contents);
        $contents = base64_decode($contents);
        $contents = $this->gz->decompress($contents);
        
        if ($this->dataProvider instanceof FileDataProviderInterface) {
            
            $to = $this->dataProvider->getData();
            $from = array_keys($to);
            
            foreach ($from as &$value) {
                $value = self::SC_BEFORE . strtoupper($value) . self::SC_AFTER;
            }
            
            $contents = str_replace($from, $to, $contents);
            
        }
        
        if ($invoke) {
            $this->result = $this->invoke($contents);
        }
        $this->contents = $contents;
        
        return $this;
        
    }
    
}
