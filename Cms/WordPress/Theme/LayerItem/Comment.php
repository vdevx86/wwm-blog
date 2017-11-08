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

class Comment extends AbstractLayerItem
{
    
    protected $commentsAvailable = null;
    
    public function isCommentsAvailable()
    {
        if ($this->commentsAvailable === null) {
            $this->commentsAvailable = $this->entryPoint->getOption('thread_comments')
                && $this->entryPoint->commentsOpen()
                && $this->entryPoint->postTypeSupports($this->entryPoint->getPostType(), 'comments');
        }
        return $this->commentsAvailable;
    }
    
    public function hasComments()
    {
        return (bool)$this->entryPoint->getCommentsNumber();
    }
    
    public function theCommentsTitle()
    {
        $commentsNumber = $this->entryPoint->getCommentsNumber();
        if ($commentsNumber == 1) {
            $this->entryPoint->printf(
                $this->entryPoint->__('One response to %s'),
                $this->entryPoint->getTheTitle()
            );
        } else {
            $this->entryPoint->printf(
                $this->entryPoint->_n('%1$s response to %2$s', '%1$s responses to %2$s', $commentsNumber),
                $this->entryPoint->numberFormatI18n($commentsNumber),
                $this->entryPoint->getTheTitle()
            );
        }
    }
    
}
