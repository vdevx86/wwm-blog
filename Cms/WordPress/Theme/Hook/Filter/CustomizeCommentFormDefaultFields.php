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

namespace Wwm\Blog\Cms\WordPress\Theme\Hook\Filter;

class CustomizeCommentFormDefaultFields extends AbstractFilter
{
    
    const TEMPLATES = [
        'author' => 'author',
        'email' => 'email',
        'url' => 'url'
    ];
    
    public function filter()
    {
        
        $this->templateParametersData->setRequireNameAndEmail($this->entryPoint->getOption('require_name_email'))
            ->setCurrentCommenter($this->entryPoint->wpGetCurrentCommenter());
        
        $this->templateParameters->set($this->templateParametersData);
        
        foreach (static::TEMPLATES as $fieldKey => $templateName) {
            if ($renderedTemplate = $this->entryPoint->renderTemplate(['form', 'comment', $templateName])) {
                $fields[$fieldKey] = $renderedTemplate;
            }
        }
        
        return $fields;
        
    }
    
}
