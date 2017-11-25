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

use Wwm\Blog\Cms\WordPress\LoadType;

class Router implements \Magento\Framework\App\RouterInterface
{
    
    const ROUTER_MODULE = 'blog';
    const ROUTER_CONTROLLER = 'wordpress';
    
    const ROUTER_ACTION_DEFAULT = 'index';
    const ROUTER_ACTION_LOGIN = 'login';
    
    const ROUTER_PARAMETER = 'query';
    
    protected $actionFactory;
    protected $config;
    protected $uriParser;
    protected $loadType;
    
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Wwm\Blog\Cms\WordPress\ConfigInterface $config,
        \Wwm\Blog\Magento\Framework\App\Request\Http\Uri\ParserInterface $uriParser,
        LoadType $loadType
    ) {
        $this->actionFactory = $actionFactory;
        $this->config = $config;
        $this->uriParser = $uriParser;
        $this->loadType = $loadType;
    }
    
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        
        $result = false;
        
        if (
                $this->config->isModuleEnabled()
            &&  $this->uriParser->getRoute() == $this->config->getRouteName()
        ) {
            
            $request->setModuleName(static::ROUTER_MODULE)
                ->setControllerName(static::ROUTER_CONTROLLER)
                ->setParam(static::ROUTER_PARAMETER, $this->uriParser->getQuery());
            
            if ($this->loadType->getType() == LoadType::LT_LOGIN) {
                $request->setActionName(static::ROUTER_ACTION_LOGIN);
            } else {
                $request->setActionName(static::ROUTER_ACTION_DEFAULT);
            }
            
            $result = $this->actionFactory->create(
                \Magento\Framework\App\Action\Forward::class,
                ['request' => $request]
            );
            
        }
        
        return $result;
        
    }
    
}
