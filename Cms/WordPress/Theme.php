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

namespace Wwm\Blog\Cms\WordPress;

class Theme implements ThemeInterface
{
    
    const IS_LIST = 'list';
    
    protected $magicTableFrom = [];
    protected $magicTableTo = [];
    
    protected $context;
    protected $filtersStorageGlobal;
    protected $filtersStorageScript;
    protected $templateParameters;
    
    protected $textDomain;
    protected $defaultFiltersPriority;
    
    protected $commentsAvailable = null;
    protected $commentsNumber = null;
    
    protected $homeURL = null;
    protected $homeURLNew = null;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        Theme\Filters\Storage\Globals $filtersStorageGlobal,
        Theme\Filters\Storage\Script $filtersStorageScript,
        Theme\Template\Parameters $templateParameters,
        $textDomain,
        $defaultFiltersPriority
    ) {
        $this->context = $context;
        $this->filtersStorageGlobal = $filtersStorageGlobal;
        $this->filtersStorageScript = $filtersStorageScript;
        $this->templateParameters = $templateParameters;
        $this->textDomain = $textDomain;
        $this->defaultFiltersPriority = abs($defaultFiltersPriority);
        $this->init();
    }
    
    public function getContext()
    {
        return $this->context;
    }
    
    public function getTemplateParameters()
    {
        return $this->templateParameters;
    }
    
    public function getHomeURL()
    {
        return $this->homeURL;
    }
    
    public function setHomeURL($homeURL)
    {
        $this->homeURL = $homeURL;
        return $this;
    }
    
    public function getHomeURLNew()
    {
        return $this->homeURLNew;
    }
    
    public function setHomeURLNew($homeURLNew)
    {
        $this->homeURLNew = $homeURLNew;
        return $this;
    }
    
    public function init()
    {
        
        $this->fillMagicTables();
        
        global $wp_filter, $merged_filters;
        
        if (!isset($wp_filter)) {
            $wp_filter = [];
        }
        if (!isset($merged_filters)) {
            $merged_filters = [];
        }
        
        $this->removeHeadActions();
        
        $this->addTitleTagSupport();
        $this->enableThemeFeatures();
        
        $this->loadThemeTextdomain($this->textDomain);
        $this->initImageSizes();
        
    }
    
    public function includeTemplateLoader()
    {
        require_once ABSPATH . WPINC . DIRECTORY_SEPARATOR . FileSystem::FN_TPLDR . FileSystem::FN_EXT;
        return $this;
    }
    
    protected function fillMagicTables()
    {
        for ($I = 65; $I < 91; ++$I) {
            $this->magicTableFrom[] = chr($I);
            $this->magicTableTo[] = '_' . chr($I + 32);
        }
    }
    
    public function getContents(\Closure $function, array $params = [])
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
    
    public function addFilter($tag, \Closure $function, $priority = null, $acceptedArgs = 1)
    {
        
        if ($priority === null) {
            $priority = $this->defaultFiltersPriority;
        }
        
        add_filter($tag, $function, $priority, $acceptedArgs);
        return true;
        
    }
    
    public function addTitleTagSupport()
    {
        $this->addThemeSupport('title-tag');
    }
    
    public function removeHeadActions()
    {
        $this->removeAction('wp_head', 'noindex', 1);
        $this->removeAction('wp_head', 'wp_no_robots');
        $this->removeAction('wp_head', 'wp_generator');
        $this->removeAction('wp_head', 'feed_links_extra', 3);
        $this->removeAction('wp_head', 'wp_oembed_add_discovery_links');
        $this->removeAction('wp_head', 'wp_oembed_add_host_js');
        $this->removeAction('wp_head', 'rest_output_link_wp_head');
        $this->removeAction('wp_head', 'rsd_link');
        $this->removeAction('wp_head', 'wlwmanifest_link');
    }
    
    public function enableAutomaticFeedLinks()
    {
        $this->addThemeSupport('automatic-feed-links');
    }
    
    public function enablePostFormats()
    {
        $this->addThemeSupport('post-formats', ['aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat']);
    }
    
    public function enableHTML5()
    {
        $this->addThemeSupport('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);
    }
    
    public function enableCSRV()
    {
        $this->addThemeSupport('customize-selective-refresh-widgets');
    }
    
    public function enablePostThumbnails()
    {
        $this->addThemeSupport('post-thumbnails');
    }
    
    public function enableThemeFeatures()
    {
        $this->enableAutomaticFeedLinks();
        $this->enablePostFormats();
        $this->enableHTML5();
        $this->enableCSRV();
        $this->enablePostThumbnails();
    }
    
    public function __($text)
    {
        $newText = __($text);
        if ($newText != $text) {
            return $newText;
        }
        return __($text, $this->textDomain);
    }
    
    public function _e($text)
    {
        echo $this->__($text);
    }
    
    public function _x($text, $context, $domain = null)
    {
        $newText = _x($text, $context, static::TEXT_DOMAIN_DEFAULT);
        if ($newText != $text) {
            return $newText;
        }
        if ($domain === null) {
            $domain = $this->textDomain;
        }
        return _x($text, $context, $domain);
    }
    
    public function _ex($text, $context, $domain = null)
    {
        if ($domain === null) {
            $domain = $this->textDomain;
        }
        echo $this->_x($text, $context, $domain);
    }
    
    public function _n($single, $plural, $number, $domain = null)
    {
        $newText = _n($single, $plural, $number, static::TEXT_DOMAIN_DEFAULT);
        if ($newText != $text) {
            return $newText;
        }
        if ($domain === null) {
            $domain = $this->textDomain;
        }
        return _n($single, $plural, $number, $domain);
    }
    
    public function _nx($single, $plural, $number, $context, $domain = null)
    {
        $newText = _nx($single, $plural, $number, $context, static::TEXT_DOMAIN_DEFAULT);
        if ($newText != $text) {
            return $newText;
        }
        if ($domain === null) {
            $domain = $this->textDomain;
        }
        return _nx($single, $plural, $number, $context, $domain);
    }
    
    public function loadTextdomainJustInTime($domain)
    {
        return _load_textdomain_just_in_time($domain);
    }
    
    public function addPostLinkFilter()
    {
        
        $function = function ($permalink, $post, $leavename) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $permalink);
        };
        
        $name = 'post_link';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function, $this->textDomain, 3);
        
        return $this;
        
    }
    
    public function addPageLinkFilter()
    {
        
        $function = function ($link, $postId, $sample) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $link);
        };
        
        $name = 'page_link';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function, null, 3);
        
        return $this;
        
    }
    
    public function addGetPagenumLinkFilter()
    {
        
        $function = function ($result) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $result);
        };
        
        $name = 'get_pagenum_link';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function);
        
        return $this;
        
    }
    
    public function addCancelCommentReplyLinkFilter()
    {
        
        $function = function ($formattedLink, $link, $text) {
            global $post;
            return str_replace($link, $this->untrailingslashit($this->getPermalink($post->ID)) . '#respond', $formattedLink);
        };
        
        $name = 'cancel_comment_reply_link';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function, $this->textDomain, 3);
        
        return $this;
        
    }
    
    public function addCommentFormFilter()
    {
        
        $function = function ($postId) {
            $this->templateParameters->setPostId($postId);
            $this->locateTemplate(['form', 'comment', 'formFooter']);
        };
        
        $name = 'comment_form';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function);
        
        return $this;
        
    }
    
    public function addTermLinkFilter()
    {
        
        $function = function ($termlink, $term, $taxonomy) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $termlink);
        };
        
        $name = 'term_link';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function, $this->textDomain, 3);
        
        return $this;
        
    }
    
    public function addCommentReplyScript()
    {
        
        if ($this->isSingular() && $this->isCommentsAvailable()) {
            
            $function = function () {
                $this->wpEnqueueScript('comment-reply');
            };
            
            $name = 'wp_enqueue_scripts';
            $this->filtersStorageGlobal->attach($function, $name);
            $this->addFilter($name, $function);
            
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
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function);
        
        return $this;
        
    }
    
    public function setNavigationMarkupTemplate()
    {
        
        $function = function ($template, $class) {
            return $this->getContents(function () {
                $this->getNavigation();
            });
        };
        
        $name = 'navigation_markup_template';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function, $this->textDomain, 2);
        
        return $this;
        
    }
    
    public function addHtmlCommentFormTop()
    {
        
        $function = function () {
            $this->locateTemplate(['form', 'comment', 'commentFormTop']);
        };
        
        $name = 'comment_form_top';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function, $this->textDomain, 0);
        
        return $this;
        
    }
    
    public function customizeCommentFormDefaults()
    {
        
        $function = function ($fields) {
            
            $templates = [
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
            
            foreach ($templates as $fieldKey => &$templateName) {
                if ($renderedTemplate = $this->renderTemplate(['form', 'comment', $templateName])) {
                    $fields[$fieldKey] = $renderedTemplate;
                }
            }
            
            return $fields;
            
        };
        
        $name = 'comment_form_defaults';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function);
        
        return $this;
        
    }
    
    public function customizeCommentFormDefaultFields()
    {
        
        $function = function () {
            
            $templates = [
                'author' => 'author',
                'email' => 'email',
                'url' => 'url'
            ];
            
            $this->templateParameters->setRequireNameAndEmail($this->getOption('require_name_email'))
                ->setCurrentCommenter($this->wpGetCurrentCommenter());
            
            foreach ($templates as $fieldKey => &$templateName) {
                if ($renderedTemplate = $this->renderTemplate(['form', 'comment', $templateName])) {
                    $fields[$fieldKey] = $renderedTemplate;
                }
            }
            
            return $fields;
            
        };
        
        $name = 'comment_form_default_fields';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function);
        
        return $this;
        
    }
    
    public function addHtmlCommentFormBottom()
    {
        
        $function = function () {
            $this->locateTemplate(['form', 'comment', 'commentFormBottom']);
        };
        
        $name = 'comment_form';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function);
        
        return $this;
        
    }
    
    public function addCanonicalUrlFilter()
    {
        
        $function = function ($canonicalURL, $post) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $canonicalURL);
        };
        
        $name = 'get_canonical_url';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function, $this->textDomain, 2);
        
        return $this;
        
    }
    
    public function addRedirectCanonicalUrlFilter()
    {
        
        $function = function ($redirectURL, $requestedURL) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $redirectURL);
        };
        
        $name = 'redirect_canonical';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function, $this->textDomain, 2);
        
        return $this;
        
    }
    
    public function addPasswordFormFilter()
    {
        
        $function = function ($output) {
            return $this->renderTemplate(['form', 'password']);
        };
        
        $name = 'the_password_form';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function);
        
        return $this;
        
    }
    
    public function addWidgetsInitFilter()
    {
        
        $function = function () {
            $this->registerSidebar([
                'name' => $this->__('Left'),
                'id' => 'sidebar-left',
                'description' => $this->__('Add widgets here to appear in your sidebar.'),
                'before_widget' => $this->renderTemplate(['sidebar', 'left', 'beforeWidget']),
                'after_widget' => $this->renderTemplate(['sidebar', 'left', 'afterWidget']),
                'before_title' => $this->renderTemplate(['sidebar', 'left', 'beforeTitle']),
                'after_title' => $this->renderTemplate(['sidebar', 'left', 'afterTitle'])
            ]);
        };
        
        $name = 'widgets_init';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function);
        
        return $this;
        
    }
    
    public function addArchivesLinkFilter()
    {
        
        $function = function ($linkHtml, $url, $text, $format, $before, $after) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $linkHtml);
        };
        
        $name = 'get_archives_link';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function, null, 6);
        
        return $this;
        
    }
    
    public function addYearLinkFilter()
    {
        
        $function = function ($yearlink, $year) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $yearlink);
        };
        
        $name = 'year_link';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function, null, 2);
        
        return $this;
        
    }
    
    public function addMonthLinkFilter()
    {
        
        $function = function ($monthlink, $year, $month) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $monthlink);
        };
        
        $name = 'month_link';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function, null, 3);
        
        return $this;
        
    }
    
    public function addDayLinkFilter()
    {
        
        $function = function ($daylink, $year, $month, $day) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $daylink);
        };
        
        $name = 'day_link';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function, null, 4);
        
        return $this;
        
    }
    
    public function addFeedLinksExtraFilter()
    {
        
        $function = function ($args = []) {
            echo str_replace(
                $this->getHomeURLNew(),
                $this->getHomeURL(),
                $this->getContents(function($args) {
                    $this->feedLinksExtra($args);
                }, [$args])
            );
        };
        
        $name = 'wp_head';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function, 3);
        
        return $this;
        
    }
    
    public function addShortLinkFilter()
    {
        
        $function = function ($shortLink, $id, $context, $allowSlugs) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $shortLink);
        };
        
        $name = 'get_shortlink';
        $this->filtersStorageGlobal->attach($function, $name);
        $this->addFilter($name, $function, null, 4);
        
        return $this;
        
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
        $this->enableThemeFeatures();
        $this->addWidgetsInitFilter();
        return $this;
    }
    
    public function addScriptHomeUrlFilter()
    {
        
        $function = function ($value, $option) {
            return str_replace($this->getHomeURL(), $this->getHomeURLNew(), $value);
        };
        
        $name = 'option_home';
        $this->filtersStorageScript->attach($function, $name);
        $this->addFilter($name, $function, $this->defaultFiltersPriority, 2);
        
        return $this;
        
    }
    
    public function enableScriptFilters()
    {
        $this->addScriptHomeUrlFilter();
        return $this;
    }
    
    public function initImageSizes()
    {
        $this->addImageSize(static::IS_LIST, 240, 300, false);
    }
    
    public function theId()
    {
        the_ID();
    }
    
    public function getTheId()
    {
        return get_the_ID();
    }
    
    public function getPostListClass($I)
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
    
    public function thePostListClass($I)
    {
        echo $this->getPostListClass($I);
    }
    
    public function thePostListThumbnail()
    {
        $this->thePostThumbnail(static::IS_LIST);
    }
    
    public function thePostListTags()
    {
        $this->theTags($this->renderTemplate(['tag', 'before']));
    }
    
    public function theCategory($separator = ', ', $parents = '', $postId = false)
    {
        the_category($separator, $parents, $postId);
    }
    
    public function locateTemplates(array $templateNames, $load = true, $requireOnce = false)
    {
        foreach ($templateNames as &$templateName) {
            array_unshift($templateName, static::SUBTEMPLATE_PREFIX);
            $templateName = implode(DIRECTORY_SEPARATOR, $templateName) . static::FN_EXT;
        }
        return locate_template($templateNames, $load, $requireOnce);
    }
    
    public function locateTemplate(array $templateName, $load = true, $requireOnce = false)
    {
        return $this->locateTemplates([$templateName], $load, $requireOnce);
    }
    
    public function renderTemplate(array $templateName, $load = true, $requireOnce = false)
    {
        return $this->getContents(function($templateName, $load, $requireOnce) {
            $this->locateTemplates([$templateName], $load, $requireOnce);
        }, [$templateName, $load, $requireOnce]);
    }
    
    public function getNavigation()
    {
        $this->locateTemplate(['navigation']);
    }
    
    public function thePrimaryButton()
    {
        $this->locateTemplate(['button', 'primary']);
    }
    
    public function theDefaultEmptyMessage()
    {
        $this->locateTemplate(['message', 'empty']);
    }
    
    public function isCommentsAvailable()
    {
        
        if ($this->commentsAvailable === null) {
            $this->commentsAvailable = $this->getOption('thread_comments')
                && $this->commentsOpen()
                && $this->postTypeSupports($this->getPostType(), 'comments');
        }
        
        return $this->commentsAvailable;
        
    }
    
    public function hasComments()
    {
        return (bool)$this->getCommentsNumber();
    }
    
    public function getCommentForm(array $args = [], $postId = null)
    {
        
        $commentsFormHtml = $this->getContents(function($args, $postId) {
            $this->commentForm($args, $postId);
        }, [$args, $postId]);
        
        return str_replace('<form ', $this->renderTemplate(['form', 'comment', 'formTag']), $commentsFormHtml);
        
    }
    
    public function theCommentForm(array $args = [], $postId = null)
    {
        echo $this->getCommentForm($args, $postId);
    }
    
    public function getCommentsTemplate($file = '/comments.php', $separateComments = false)
    {
        return $this->getContents(function($file, $separateComments) {
            $this->commentsTemplate($file, $separateComments);
        }, [$file, $separateComments]);
    }
    
    public function theCommentsTemplate($file = '/comments.php', $separateComments = false)
    {
        echo $this->getCommentsTemplate($file, $separateComments);
    }
    
    public function theCommentsTitle()
    {
        $commentsNumber = $this->getCommentsNumber();
        if ($commentsNumber == 1) {
            printf($this->__('One response to %s'), $this->getTheTitle());
        } else {
            printf($this->_n('%1$s response to %2$s', '%1$s responses to %2$s', $commentsNumber), $this->numberFormatI18n($commentsNumber), $this->getTheTitle());
        }
    }
    
    public function getDefaultAvatar()
    {
        return $this->getAvatar($this->getTheAuthorMeta('user_email'), 80, null, $this->getTheAuthor());
    }
    
    public function theDefaultAvatar()
    {
        echo $this->getDefaultAvatar();
    }
    
    public function theDate()
    {
        echo $this->getTheDate();
    }
    
    public function getLoginForm(array $args = [])
    {
        
        $redirect = 'http';
        if ($this->isSsl()) {
            $redirect .= 's';
        }
        $redirect .= '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        
        $defaults = [
            'redirect' => $redirect,
            'form_id' => 'loginform',
            'label_username' => $this->__('Username or Email'),
            'label_password' => $this->__('Password'),
            'label_remember' => $this->__('Remember Me'),
            'label_log_in' => $this->__('Log In'),
            'id_username' => 'user_login',
            'id_password' => 'user_pass',
            'id_remember' => 'rememberme',
            'id_submit' => 'wp-submit',
            'remember' => true,
            'value_username' => '',
            'value_remember' => false
        ];
        
        $args = $this->wpParseArgs($args, $this->applyFilters('login_form_defaults', $defaults));
        $this->templateParameters->setData($args)
            ->setTop($this->applyFilters('login_form_top', '', $args))
            ->setMiddle($this->applyFilters('login_form_middle', '', $args))
            ->setBottom($this->applyFilters('login_form_bottom', '', $args));
        
        return $this->renderTemplate(['form', 'login']);
        
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
        return $this->applyDynamicSidebarSpecificFilters($this->getContents(function ($id) {
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
    
    public function __call($name, array $args)
    {
        $name = str_replace($this->magicTableFrom, $this->magicTableTo, $name);
        return $name(...$args);
    }
    
}
