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

namespace Wwm\Blog\Controller;

class Router implements \Magento\Framework\App\RouterInterface
{
    
    const URI_DELIMITER = '/';
    
    const ROUTER_MODULE = 'blog';
    const ROUTER_CONTROLLER = 'wordpress';
    
    const ROUTER_ACTION_DEFAULT = 'index';
    const ROUTER_ACTION_LOGIN = 'login';
    
    const ROUTER_PARAMETER = 'query';
    const ROUTER_NAME_DEFAULT = 'blog';
    
    // Load types
    const LT_DEFAULT = 0;
    const LT_LOGIN = 1;
    
    protected $actionFactory;
    protected $config;
    
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Wwm\Blog\Helper\Config $config
    ) {
        $this->actionFactory = $actionFactory;
        $this->config = $config;
    }
    
    public function recognizeLoadType($query)
    {
        
        $result = static::LT_DEFAULT;
        
        if ($query) {
            if ($path = parse_url($query, PHP_URL_PATH)) {
                if ($path = explode(static::URI_DELIMITER, $path)) {
                    if ($scriptName = array_pop($path)) {
                        
                        if ($scriptName == WordPress\FileSystem::FN_LOGIN . WordPress\FileSystem::FN_EXT) {
                            $result = static::LT_LOGIN;
                        }
                        
                    }
                }
            }
        }
        
        return $result;
        
    }
    
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        
        $result = false;
        
        if ($this->config->isModuleEnabled()) {
            if ($requestURI = ltrim($request->getRequestUri(), static::URI_DELIMITER)) {
                $requestURI = explode(static::URI_DELIMITER, $requestURI, 2);
                if (
                        is_array($requestURI)
                    &&  count($requestURI) > 0
                    &&  $requestURI[0] == $this->config->getRouteName()
                ) {
                    
                    if (!isset($requestURI[1])) {
                        $requestURI[1] = '';
                    }
                    
                    $request->setModuleName(static::ROUTER_MODULE)
                        ->setControllerName(static::ROUTER_CONTROLLER)
                        ->setParam(static::ROUTER_PARAMETER, $requestURI[1]);
                    
                    switch ($this->recognizeLoadType($requestURI[1])) {
                        case static::LT_LOGIN:
                            $request->setActionName(static::ROUTER_ACTION_LOGIN);
                            break;
                        default:
                            $request->setActionName(static::ROUTER_ACTION_DEFAULT);
                    }
                    
                    $result = $this->actionFactory->create(
                        \Magento\Framework\App\Action\Forward::class,
                        ['request' => $request]
                    );
                    
                }
            }
        }
        
        return $result;
        
    }
    
}
