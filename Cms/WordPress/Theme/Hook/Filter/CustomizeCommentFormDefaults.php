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

class CustomizeCommentFormDefaults extends AbstractFilter
{
    
    const TEMPLATES = [
        'fields' => 'fields',
        'comment_field' => 'commentField',
        'must_log_in' => 'mustLogIn',
        'logged_in_as' => 'loggedInAs',
        'comment_notes_before' => 'commentNotesBefore',
        'comment_notes_after' => 'commentNotesAfter',
        'action' => 'action',
        'id_form' => 'idForm',
        'id_submit' => 'idSubmit',
        'class_form' => 'classForm',
        'class_submit' => 'classSubmit',
        'name_submit' => 'nameSubmit',
        'title_reply' => 'titleReply',
        'title_reply_to' => 'titleReplyTo',
        'title_reply_before' => 'titleReplyBefore',
        'title_reply_after' => 'titleReplyAfter',
        'cancel_reply_before' => 'cancelReplyBefore',
        'cancel_reply_after' => 'cancelReplyAfter',
        'cancel_reply_link' => 'cancelReplyLink',
        'label_submit' => 'labelSubmit',
        'submit_button' => 'submitButton',
        'submit_field' => 'submitField',
        'format' => 'format'
    ];
    
    public function filter($fields)
    {
        
        foreach (static::TEMPLATES as $fieldKey => $templateName) {
            if ($renderedTemplate = $this->entryPoint->renderTemplate(['form', 'comment', $templateName])) {
                $fields[$fieldKey] = $renderedTemplate;
            }
        }
        
        return $fields;
        
    }
    
}
