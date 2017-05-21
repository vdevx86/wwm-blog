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

namespace Wwm\Blog\Cms\WordPress\Theme;

class Wwm
{
    
    const TEXT_DOMAIN = 'wwm';
    const TEXT_DOMAIN_DEFAULT = 'default';
    
    const DEFAULT_FILTERS_PRIORITY = 10;
    const TEMPLATE_PREFIX = 'templates';
    
    const IS_LIST = 'list';
    const IS_LIST_WIDTH = 240;
    const IS_LIST_HEIGHT = 300;
    const IS_LIST_CROP = false;
    
    const DEFAULT_AVATAR_SIZE = 80;
    
    const MAGIC_TABLE_FROM = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G',
        'H', 'I', 'J', 'K', 'L', 'M', 'N',
        'O', 'P', 'Q', 'R', 'S', 'T', 'U',
        'V', 'W', 'X', 'Y', 'Z'
    ];
    
    const MAGIC_TABLE_TO = [
        '_a', '_b', '_c', '_d', '_e', '_f', '_g',
        '_h', '_i', '_j', '_k', '_l', '_m', '_n',
        '_o', '_p', '_q', '_r', '_s', '_t', '_u',
        '_v', '_w', '_x', '_y', '_z'
    ];
    
    protected $filtersStorageGlobal = null;
    protected $filtersStorageScript = null;
    
    private $commentsAvailable = null;
    private $commentsNumber = null;
    
    public static function getContents(\Closure $function, array $params = [])
    {
        
        ob_start();
        
        if ($params) {
            $function(...$params);
        } else {
            $function();
        }
        
        $contents = ob_get_contents();
        ob_end_clean();
        
        return $contents;
        
    }
    
    public static function addFilter($tag, \Closure $function, $priority = null, $acceptedArgs = 1)
    {
        
        if ($priority === null) {
            $priority = static::DEFAULT_FILTERS_PRIORITY;
        }
        
        add_filter($tag, $function, $priority, $acceptedArgs);
        return true;
        
    }
    
    public static function addTitleTagSupport()
    {
        static::addThemeSupport('title-tag');
    }
    
    public static function removeHeadActions()
    {
        static::removeAction('wp_head', 'noindex', 1);
        static::removeAction('wp_head', 'wp_no_robots');
        static::removeAction('wp_head', 'wp_generator');
        static::removeAction('wp_head', 'feed_links_extra', 3);
        static::removeAction('wp_head', 'wp_oembed_add_discovery_links');
        static::removeAction('wp_head', 'wp_oembed_add_host_js');
        static::removeAction('wp_head', 'rest_output_link_wp_head');
        static::removeAction('wp_head', 'rsd_link');
        static::removeAction('wp_head', 'wlwmanifest_link');
    }
    
    public static function enableAutomaticFeedLinks()
    {
        static::addThemeSupport('automatic-feed-links');
    }
    
    public static function enablePostFormats()
    {
        static::addThemeSupport('post-formats', ['aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat']);
    }
    
    public static function enableHTML5()
    {
        static::addThemeSupport('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    }
    
    public static function enableCSRV()
    {
        static::addThemeSupport('customize-selective-refresh-widgets');
    }
    
    public static function enablePostThumbnails()
    {
        static::addThemeSupport('post-thumbnails');
    }
    
    public static function enableThemeFeatures()
    {
        static::enableAutomaticFeedLinks();
        static::enablePostFormats();
        static::enableHTML5();
        static::enableCSRV();
        static::enablePostThumbnails();
    }
    
    public static function __($text)
    {
        $newText = __($text);
        if ($newText != $text) {
            return $newText;
        }
        return __($text, static::TEXT_DOMAIN);
    }
    
    public static function _e($text)
    {
        echo static::__($text);
    }
    
    public static function _x($text, $context, $domain = null)
    {
        $newText = _x($text, $context, static::TEXT_DOMAIN_DEFAULT);
        if ($newText != $text) {
            return $newText;
        }
        if ($domain === null) {
            $domain = static::TEXT_DOMAIN;
        }
        return _x($text, $context, $domain);
    }
    
    public static function _ex($text, $context, $domain = null)
    {
        if ($domain === null) {
            $domain = static::TEXT_DOMAIN;
        }
        echo static::_x($text, $context, $domain);
    }
    
    public static function _n($single, $plural, $number, $domain = null)
    {
        $newText = _n($single, $plural, $number, static::TEXT_DOMAIN_DEFAULT);
        if ($newText != $text) {
            return $newText;
        }
        if ($domain === null) {
            $domain = static::TEXT_DOMAIN;
        }
        return _n($single, $plural, $number, $domain);
    }
    
    public static function _nx($single, $plural, $number, $context, $domain = null)
    {
        $newText = _nx($single, $plural, $number, $context, static::TEXT_DOMAIN_DEFAULT);
        if ($newText != $text) {
            return $newText;
        }
        if ($domain === null) {
            $domain = static::TEXT_DOMAIN;
        }
        return _nx($single, $plural, $number, $context, $domain);
    }
    
    public static function loadTextdomainJustInTime($domain)
    {
        return _load_textdomain_just_in_time($domain);
    }
    
    public function getFiltersStorageGlobal()
    {
        return $this->filtersStorageGlobal;
    }
    
    public function setFiltersStorageGlobal(\SplObjectStorage $filtersStorageGlobal = null)
    {
        $this->filtersStorageGlobal = $filtersStorageGlobal;
        return $this;
    }
    
    public function addPostLinkFilter()
    {
        
        $function = function ($permalink, $post, $leavename) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $permalink);
        };
        
        $name = 'post_link';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function, static::TEXT_DOMAIN, 3);
        
        return $this;
        
    }
    
    public function addPageLinkFilter()
    {
        
        $function = function ($link, $postId, $sample) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $link);
        };
        
        $name = 'page_link';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function, null, 3);
        
        return $this;
        
    }
    
    public function addGetPagenumLinkFilter()
    {
        
        $function = function ($result) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $result);
        };
        
        $name = 'get_pagenum_link';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function);
        
        return $this;
        
    }
    
    public function addCancelCommentReplyLinkFilter()
    {
        
        $function = function ($formattedLink, $link, $text) {
            global $post;
            return str_replace($link, static::untrailingslashit(static::getPermalink($post->ID)) . '#respond', $formattedLink);
        };
        
        $name = 'cancel_comment_reply_link';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function, static::TEXT_DOMAIN, 3);
        
        return $this;
        
    }
    
    public function addCommentFormFilter()
    {
        
        $function = function ($postId) {
            echo '<input type="hidden" name="redirect_to" value="', static::getPermalink($postId), '"/>';
        };
        
        $name = 'comment_form';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function);
        
        return $this;
        
    }
    
    public function addTermLinkFilter()
    {
        
        $function = function ($termlink, $term, $taxonomy) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $termlink);
        };
        
        $name = 'term_link';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function, static::TEXT_DOMAIN, 3);
        
        return $this;
        
    }
    
    public function addCommentReplyScript()
    {
        
        if (static::isSingular() && $this->isCommentsAvailable()) {
            
            $function = function () {
                static::wpEnqueueScript('comment-reply');
            };
            
            $name = 'wp_enqueue_scripts';
            $this->getFiltersStorageGlobal()->attach($function, $name);
            static::addFilter($name, $function);
            
        }
        
        return $this;
        
    }
    
    public function removeDocumentTitleParts()
    {
        
        $function = function ($title) {
            unset($title['tagline'], $title['site']);
            return $title;
        };
        
        $name = 'document_title_parts';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function);
        
        return $this;
        
    }
    
    public function setNavigationMarkupTemplate()
    {
        
        $function = function ($template, $class) {
            return static::getContents(function () {
                static::getNavigation();
            });
        };
        
        $name = 'navigation_markup_template';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function, static::TEXT_DOMAIN, 2);
        
        return $this;
        
    }
    
    public function addHtmlCommentFormTop()
    {
        
        $function = function () {
            echo '<fieldset class="fieldset">';
        };
        
        $name = 'comment_form_top';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function, static::TEXT_DOMAIN, 0);
        
        return $this;
        
    }
    
    public function customizeCommentFormDefaults()
    {
        
        $function = function ($fields) {
            
            $fields['comment_field'] =
            '<div class="field comment-form-comment required">' .
                '<label class="label" for="comment"><span>' . static::_x('Comment', 'noun') . '</span></label>' .
                '<div class="control">' .
                    '<textarea id="comment" class="input-text" name="comment" cols="45" rows="8" maxlength="65525" aria-required="true" required="required" data-validate="{required:true}"></textarea>' .
                '</div>' .
            '</div>';
            
            if (static::getOption('require_name_email')) {
                $requiredText = '<span class="fields-required">' . sprintf(static::__('Required fields are marked %s'), '<span class="required">*</span>') . '</span>';
            } else {
                $requiredText = '';
            }
            
            $fields['comment_notes_before'] =
            '<legend class="legend comment-notes">' .
                '<span id="email-notes">' . static::__('Your email address will not be published.') . '</span>' .
                $requiredText .
            '</legend>';
            
            $fields['title_reply_before'] = '<div id="reply-title" class="comment-reply-title">';
            $fields['title_reply_after'] = '</div>';
            
            $fields['submit_button'] =
            '<div class="primary">' .
                '<input type="submit" name="%1$s" id="%2$s" class="action primary %3$s" value="%4$s"/>' .
            '</div>';
            
            $fields['submit_field'] = '<div class="actions-toolbar form-submit">%1$s %2$s</div>';
            
            return $fields;
            
        };
        
        $name = 'comment_form_defaults';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function);
        
        return $this;
        
    }
    
    public function customizeCommentFormDefaultFields()
    {
        
        $function = function () {
            
            $requireNameAndEmail = static::getOption('require_name_email');
            $currentCommenter = static::wpGetCurrentCommenter();
            
            $fields['author'] =
            '<div class="field comment-form-author' . ($requireNameAndEmail ? ' required' : '') . '">' .
                '<label class="label" for="author"><span>' . static::__('Name') . '</span></label>' .
                '<div class="control">' .
                    '<input type="text" id="author" class="input-text" name="author" value="' . static::escAttr($currentCommenter['comment_author']) . '" size="30" maxlength="245"' .
                        ($requireNameAndEmail ? ' aria-required="true" required="required" data-validate="{required:true}"' : '') . '/>' .
                '</div>' .
            '</div>';
            
            $fields['email'] =
            '<div class="field comment-form-email' . ($requireNameAndEmail ? ' required' : '') . '">' .
                '<label class="label" for="email"><span>' . static::__('Email') . '</span></label>' .
                '<div class="control">' .
                    '<input type="email" id="email" class="input-text" name="email" value="' . static::escAttr($currentCommenter['comment_author_email']) .
                        '" size="30" maxlength="100" aria-describedby="email-notes"' .
                            ($requireNameAndEmail ? ' aria-required="true"' : '') . ' data-validate="{' .
                            ($requireNameAndEmail ? 'required:true,' : '') . '\'validate-email\':true}"/>' .
                '</div>' .
            '</div>';
            
            $fields['url'] =
            '<div class="field comment-form-url">' .
                '<label class="label" for="url"><span>' . static::__('Website') . '</span></label>' .
                '<div class="control">' .
                    '<input type="url" id="url" class="input-text" name="url" value="' . static::escAttr($currentCommenter['comment_author_url']) .
                        '" size="30" maxlength="200" data-validate="{\'validate-url\':true}"/>' .
                '</div>' .
            '</div>';
            
            return $fields;
            
        };
        
        $name = 'comment_form_default_fields';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function);
        
        return $this;
        
    }
    
    public function addHtmlCommentFormBottom()
    {
        
        $function = function () {
            echo '</fieldset>';
        };
        
        $name = 'comment_form';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function);
        
        return $this;
        
    }
    
    public function addCanonicalUrlFilter()
    {
        
        $function = function ($canonicalURL, $post) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $canonicalURL);
        };
        
        $name = 'get_canonical_url';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function, static::TEXT_DOMAIN, 2);
        
        return $this;
        
    }
    
    public function addRedirectCanonicalUrlFilter()
    {
        
        $function = function ($redirectURL, $requestedURL) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $redirectURL);
        };
        
        $name = 'redirect_canonical';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function, static::TEXT_DOMAIN, 2);
        
        return $this;
        
    }
    
    public function addPasswordFormFilter()
    {
        
        $function = function ($output) {
            return
            '<form action="' . static::escUrl(static::setUrlScheme($this->getHomeURLNew() . '/wp-login.php?action=postpass', 'login_post')) . '" method="post" class="post-password-form" data-hasrequired="' .
                    static::__('* Required Fields') . '" novalidate="novalidate" data-mage-init=\'{"validation":{}}\'>' .
                '<fieldset class="fieldset">' .
                    '<legend class="legend">' . static::__('This content is password protected. To view it please enter your password below:') . '</legend>' .
                    '<div class="field required">' .
                        '<label class="label" for="post-password"><span>' . static::__('Password:') . '</span></label>' .
                        '<div class="control">' .
                            '<input type="password" name="post_password" id="post-password" class="input-text" value="" size="20" required="required" aria-required="true" data-validate="{required:true}"/>' .
                        '</div>' .
                    '</div>' .
                    '<div class="actions-toolbar">' .
                        '<div class="primary">' .
                            '<button type="submit" class="action submit primary">' .
                                '<span>' . static::escAttrX('Enter', 'post password form') . '</span>' .
                            '</button>' .
                        '</div>' .
                    '</div>' .
                '</fieldset>' .
            '</form>';
        };
        
        $name = 'the_password_form';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function);
        
        return $this;
        
    }
    
    public function addWidgetsInitFilter()
    {
        
        $function = function () {
            
            static::registerSidebar([
                'name' => static::__('Left'),
                'id' => 'sidebar-left',
                'description' => static::__('Add widgets here to appear in your sidebar.'),
                'before_widget' => '<div id="%1$s" class="widget block widget-blog %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="widget-title block-title"><strong>',
                'after_title' => '</strong></div>'
            ]);
            
        };
        
        $name = 'widgets_init';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function);
        
        return $this;
        
    }
    
    public function addArchivesLinkFilter()
    {
        
        $function = function ($linkHtml, $url, $text, $format, $before, $after) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $linkHtml);
        };
        
        $name = 'get_archives_link';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function, null, 6);
        
        return $this;
        
    }
    
    public function addYearLinkFilter()
    {
        
        $function = function ($yearlink, $year) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $yearlink);
        };
        
        $name = 'year_link';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function, null, 2);
        
        return $this;
        
    }
    
    public function addMonthLinkFilter()
    {
        
        $function = function ($monthlink, $year, $month) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $monthlink);
        };
        
        $name = 'month_link';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function, null, 3);
        
        return $this;
        
    }
    
    public function addDayLinkFilter()
    {
        
        $function = function ($daylink, $year, $month, $day) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $daylink);
        };
        
        $name = 'day_link';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function, null, 4);
        
        return $this;
        
    }
    
    public function addFeedLinksExtraFilter()
    {
        
        $function = function ($args = []) {
            echo str_replace(
                $this->getHomeURLNew(),
                $this->getHomeURL(),
                static::getContents(function($args) {
                    static::feedLinksExtra($args);
                }, [$args])
            );
        };
        
        $name = 'wp_head';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function, 3);
        
        return $this;
        
    }
    
    public function addShortLinkFilter()
    {
        
        $function = function ($shortLink, $id, $context, $allowSlugs) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $shortLink);
        };
        
        $name = 'get_shortlink';
        $this->getFiltersStorageGlobal()->attach($function, $name);
        static::addFilter($name, $function, null, 4);
        
        return $this;
        
    }
    
    public function disableGlobalFilters()
    {
        
        if ($filtersStorage = $this->getFiltersStorageGlobal()) {
            if ($filtersStorage instanceof \SplObjectStorage) {
                if (count($filtersStorage) > 0) {
                    $filtersStorage->rewind();
                    while ($filtersStorage->valid()) {
                        static::removeFilter($filtersStorage->getInfo(), $filtersStorage->current());
                        $filtersStorage->next();
                    }
                }
            }
        }
        
        return $this->setFiltersStorageGlobal();
        
    }
    
    public function enableGlobalFilters()
    {
        
        $this->addPostLinkFilter();
        $this->addPageLinkFilter();
        
        $this->addGetPagenumLinkFilter();
        $this->addCancelCommentReplyLinkFilter();
        $this->addCommentFormFilter();
        $this->addTermLinkFilter();
        
        $this->removeDocumentTitleParts();
        $this->setNavigationMarkupTemplate();
        
        $this->addHtmlCommentFormTop();
        $this->customizeCommentFormDefaultFields();
        $this->customizeCommentFormDefaults();
        $this->addHtmlCommentFormBottom();
        $this->addPasswordFormFilter();
        
        $this->addCanonicalUrlFilter();
        $this->addRedirectCanonicalUrlFilter();
        
        $this->addWidgetsInitFilter();
        $this->addArchivesLinkFilter();
        
        $this->addYearLinkFilter();
        $this->addMonthLinkFilter();
        $this->addDayLinkFilter();
        
        $this->addFeedLinksExtraFilter();
        $this->addShortLinkFilter();
        
        return $this;
        
    }
    
    public function enableLateGlobalFilters()
    {
        $this->addCommentReplyScript();
        return $this;
    }
    
    public function enableAdminGlobalFilters()
    {
        static::enableThemeFeatures();
        $this->addWidgetsInitFilter();
        return $this;
    }
    
    public function getFiltersStorageScript()
    {
        return $this->filtersStorageScript;
    }
    
    public function setFiltersStorageScript(\SplObjectStorage $filtersStorageScript = null)
    {
        $this->filtersStorageScript = $filtersStorageScript;
        return $this;
    }
    
    public function addScriptHomeUrlFilter()
    {
        
        $function = function ($value, $option) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $value);
        };
        
        $name = 'option_home';
        $this->getFiltersStorageScript()->attach($function, $name);
        static::addFilter($name, $function, static::DEFAULT_FILTERS_PRIORITY, 2);
        
        return $this;
        
    }
    
    public function enableScriptFilters()
    {
        $this->addScriptHomeUrlFilter();
        return $this;
    }
    
    public static function initImageSizes()
    {
        static::addImageSize(
            static::IS_LIST,
            static::IS_LIST_WIDTH,
            static::IS_LIST_HEIGHT,
            static::IS_LIST_CROP
        );
    }
    
    public function theId()
    {
        the_ID();
    }
    
    public function getTheId()
    {
        return get_the_ID();
    }
    
    public static function getPostListClass($I)
    {
        
        $classes = ['post-item', 'item', 'item' . $I];
        
        if ($I & 1) {
            $classes[] = 'odd';
        }
        if ($I == 1) {
            $classes[] = 'first';
        }
        
        global $wp_query;
        if ($I == $wp_query->post_count) {
            $classes[] = 'last';
        }
        
        return implode(' ', $classes);
        
    }
    
    public static function thePostListClass($I)
    {
        echo static::getPostListClass($I);
    }
    
    public static function thePostListThumbnail()
    {
        static::thePostThumbnail(static::IS_LIST);
    }
    
    public static function thePostListTags()
    {
        static::theTags('<strong>' . __('Tags: ') . '</strong>');
    }
    
    public function theCategory($separator = ', ', $parents = '', $postId = false)
    {
        the_category($separator, $parents, $postId);
    }
    
    public static function getTemplatePart($slug, $name = '')
    {
        get_template_part(static::TEMPLATE_PREFIX . DIRECTORY_SEPARATOR . $slug, $name);
    }
    
    public static function getNavigation()
    {
        static::getTemplatePart('navigation');
    }
    
    public static function thePrimaryButton()
    {
        static::getTemplatePart('button', 'primary');
    }
    
    public static function theDefaultEmptyMessage()
    {
        static::getTemplatePart('message', 'empty');
    }
    
    public function isCommentsAvailable()
    {
        
        if ($this->commentsAvailable === null) {
            $this->commentsAvailable = static::getOption('thread_comments')
                && static::commentsOpen()
                && static::postTypeSupports(static::getPostType(), 'comments');
        }
        
        return $this->commentsAvailable;
        
    }
    
    public static function hasComments()
    {
        return (bool)static::getCommentsNumber();
    }
    
    public static function getCommentForm(array $args = [], $postId = null)
    {
        
        $commentsFormHtml = static::getContents(function($args, $postId) {
            static::commentForm($args, $postId);
        }, [$args, $postId]);
        
        return str_replace('<form ', '<form data-mage-init=\'{"validation":{}}\' ', $commentsFormHtml);
        
    }
    
    public static function theCommentForm(array $args = [], $postId = null)
    {
        echo static::getCommentForm($args, $postId);
    }
    
    public static function getCommentsTemplate($file = '/comments.php', $separateComments = false)
    {
        return static::getContents(function($file, $separateComments) {
            static::commentsTemplate($file, $separateComments);
        }, [$file, $separateComments]);
    }
    
    public static function theCommentsTemplate($file = '/comments.php', $separateComments = false)
    {
        echo static::getCommentsTemplate($file, $separateComments);
    }
    
    public static function theCommentsTitle()
    {
        
        $commentsNumber = static::getCommentsNumber();
        switch ($commentsNumber) {
            case 1:
                printf(static::__('One response to %s'), static::getTheTitle());
                break;
            default:
                printf(static::_n('%1$s response to %2$s', '%1$s responses to %2$s', $commentsNumber), static::numberFormatI18n($commentsNumber), static::getTheTitle());
        }
        
    }
    
    public static function getDefaultAvatar()
    {
        return static::getAvatar(
            static::getTheAuthorMeta('user_email'),
            static::DEFAULT_AVATAR_SIZE,
            null,
            static::getTheAuthor()
        );
    }
    
    public static function theDefaultAvatar()
    {
        echo static::getDefaultAvatar();
    }
    
    public static function theDate()
    {
        echo static::getTheDate();
    }
    
    public function getLoginForm(array $args = [])
    {
        
        $defaults = [
            'redirect' => (static::isSsl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            'form_id' => 'loginform',
            'label_username' => static::__('Username or Email'),
            'label_password' => static::__('Password'),
            'label_remember' => static::__('Remember Me'),
            'label_log_in' => static::__('Log In'),
            'id_username' => 'user_login',
            'id_password' => 'user_pass',
            'id_remember' => 'rememberme',
            'id_submit' => 'wp-submit',
            'remember' => true,
            'value_username' => '',
            'value_remember' => false
        ];
        
        $args = static::wpParseArgs($args, static::applyFilters('login_form_defaults', $defaults));
        $loginFormTop = static::applyFilters('login_form_top', '', $args);
        $loginFormMiddle = static::applyFilters('login_form_middle', '', $args);
        $loginFormBottom = static::applyFilters('login_form_bottom', '', $args);
        
        return
        '<form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="' . static::escUrl($this->getHomeURLNew() . '/wp-login.php', 'login_post') . '" method="post" data-hasrequired="' . static::__('* Required Fields') . '" novalidate="novalidate" data-mage-init=\'{"validation":{}}\'>' .
            '<fieldset class="fieldset">' .
                
                $loginFormTop .
                
                '<div class="field login-username required">' .
                    '<label class="label" for="' . static::escAttr($args['id_username']) . '"><span>' . static::escHtml($args['label_username']) . '</span></label>' .
                    '<div class="control">' .
                        '<input type="text" name="log" id="' . static::escAttr($args['id_username']) . '" class="input input-text" value="' . static::escAttr($args['value_username']) . '" required="required" aria-required="true" data-validate="{required:true}"/>' .
                    '</div>' .
                '</div>' .
                
                '<div class="field login-password required">' .
                    '<label class="label" for="' . static::escAttr($args['id_password']) . '"><span>' . static::escHtml($args['label_password']) . '</span></label>' .
                    '<div class="control">' .
                        '<input type="password" name="pwd" id="' . static::escAttr($args['id_password']) . '" class="input input-text" value="" required="required" aria-required="true" data-validate="{required:true}"/>' .
                    '</div>' .
                '</div>' .
                
                $loginFormMiddle .
                
                (
                    $args['remember']
                ?
                    '<div class="field choice login-remember">' .
                        '<input type="checkbox" id="' . static::escAttr($args['id_remember']) . '" name="rememberme" value="forever" class="checkbox"' . ($args['value_remember'] ? ' checked="checked"' : '') . '/>' .
                        '<label for="' . static::escAttr($args['id_remember']) . '" class="label"><span>' . static::escHtml($args['label_remember']) . '</span></label>' .
                    '</div>'
                :
                    ''
                ) .
                
                '<div class="actions-toolbar">' .
                    '<div class="primary">' .
                        '<button type="submit" id="' . static::escAttr($args['id_submit']) . '" class="action submit login-submit primary">' .
                            '<span>' . static::escAttr($args['label_log_in']) . '</span>' .
                        '</button>' .
                        '<input type="hidden" name="redirect_to" value="' . static::escUrl(str_replace($this->getHomeURL(), $this->getHomeURLNew(), $args['redirect'])) . '"/>' .
                    '</div>' .
                '</div>' .
                
                $loginFormBottom .
                
            '</fieldset>' .
        '</form>';
        
    }
    
    public function theLoginForm(array $args = [])
    {
        echo $this->getLoginForm($args);
    }
    
    public function applyDynamicSidebarSpecificFilters($html)
    {
        
        $S1 = 'location.href = "';
        $S2 = '/?cat=" + dropdown.options[ dropdown.selectedIndex ].value;';
        
        return str_replace(
            $S1 . $this->getHomeURL() . $S2,
            $S1 . $this->getHomeURLNew() . $S2,
            $html
        );
        
    }
    
    public function getDynamicSidebar($id)
    {
        return $this->applyDynamicSidebarSpecificFilters(static::getContents(function ($id) {
            dynamic_sidebar($id);
        }, [$id]));
    }
    
    public function theDynamicSidebar($id)
    {
        echo $this->getDynamicSidebar($id);
    }
    
    public function dynamicSidebar($id)
    {
        echo $this->getDynamicSidebar($id);
    }
    
    public function __construct()
    {
        
        $this->filtersStorageGlobal = new \SplObjectStorage;
        $this->filtersStorageScript = new \SplObjectStorage;
        
        global $wp_filter, $merged_filters;
        
        if (!isset($wp_filter)) {
            $wp_filter = [];
        }
        
        if (!isset($merged_filters)) {
            $merged_filters = [];
        }
        
        static::removeHeadActions();
        
        static::addTitleTagSupport();
        static::enableThemeFeatures();
        
        static::loadThemeTextdomain(static::TEXT_DOMAIN);
        static::initImageSizes();
        
    }
    
    private static function callMagic($name, array $args)
    {
        $name = str_replace(static::MAGIC_TABLE_FROM, static::MAGIC_TABLE_TO, $name);
        return $name(...$args);
    }
    
    public function __call($name, array $args)
    {
        return $this->callMagic($name, $args);
    }
    
    public static function __callStatic($name, array $args)
    {
        return static::callMagic($name, $args);
    }
    
}
