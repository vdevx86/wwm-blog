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

class CommentTemplate extends AbstractLayerItem
{
    
    public function getCommentForm(array $args = [], $postId = null)
    {
        
        $commentsFormHtml = $this->entryPoint->getContents(function($args, $postId) {
            $this->entryPoint->commentForm($args, $postId);
        }, [$args, $postId]);
        
        return str_replace(
            '<form ',
            $this->entryPoint->renderTemplate(['form', 'comment', 'formTag']),
            $commentsFormHtml
        );
        
    }
    
    public function theCommentForm(array $args = [], $postId = null)
    {
        echo $this->entryPoint->getCommentForm($args, $postId);
    }
    
    public function getCommentsTemplate($file = '/comments.php', $separateComments = false)
    {
        return $this->entryPoint->getContents(function($file, $separateComments) {
            $this->entryPoint->commentsTemplate($file, $separateComments);
        }, [$file, $separateComments]);
    }
    
    public function theCommentsTemplate($file = '/comments.php', $separateComments = false)
    {
        echo $this->entryPoint->getCommentsTemplate($file, $separateComments);
    }
    
}
