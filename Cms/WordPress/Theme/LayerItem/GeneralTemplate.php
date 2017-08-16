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

namespace Wwm\Blog\Cms\WordPress\Theme\LayerItem;

class GeneralTemplate extends AbstractLayerItem
{
    
    public function theDate()
    {
        echo $this->entryPoint->getTheDate();
    }
    
    public function getLoginForm(array $args = [])
    {
        
        $redirect = 'http';
        if ($this->entryPoint->isSsl()) {
            $redirect .= 's';
        }
        $redirect .= '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        
        $defaults = [
            'redirect' => $redirect,
            'form_id' => 'loginform',
            'label_username' => $this->entryPoint->__('Username or Email'),
            'label_password' => $this->entryPoint->__('Password'),
            'label_remember' => $this->entryPoint->__('Remember Me'),
            'label_log_in' => $this->entryPoint->__('Log In'),
            'id_username' => 'user_login',
            'id_password' => 'user_pass',
            'id_remember' => 'rememberme',
            'id_submit' => 'wp-submit',
            'remember' => true,
            'value_username' => '',
            'value_remember' => false
        ];
        
        $args = $this->entryPoint->wpParseArgs(
            $args,
            $this->entryPoint->applyFilters('login_form_defaults', $defaults)
        );
        
        $this->templateParametersData->addData($args)
            ->setTop($this->entryPoint->applyFilters('login_form_top', '', $args))
            ->setMiddle($this->entryPoint->applyFilters('login_form_middle', '', $args))
            ->setBottom($this->entryPoint->applyFilters('login_form_bottom', '', $args));
        
        $this->templateParameters->set($this->templateParametersData);
        return $this->entryPoint->renderTemplate(['form', 'login']);
        
    }
    
    public function theLoginForm(array $args = [])
    {
        echo $this->entryPoint->getLoginForm($args);
    }
    
}
