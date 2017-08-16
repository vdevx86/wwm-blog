<?php /* @codingStandardsIgnoreFile */ global $theme; ?>
<section id="blog" <?php $theme->bodyClass(); ?>>
    <?php if ($theme->havePosts()): $theme->thePost(); ?>
    <article id="post-<?php $theme->theId(); ?>" <?php $theme->postClass(); ?>>
        <div class="post-meta">
            <?php $theme->theDefaultAvatar(); ?>
            <div class="post-author"><strong><?php $theme->_e('Author'); ?>:</strong><br/><em><?php $theme->theAuthor(); ?></em></div>
            <div class="post-date"><strong><?php $theme->_e('Published'); ?>:</strong><br/><em><?php $theme->theDate(); ?></em></div>
        </div>
        <div class="post-links">
            <div class="post-categories"><strong><?php $theme->_e('Categories'); ?>:</strong> <?php $theme->theCategory(); ?></div>
            <div class="post-tags"><?php $theme->thePostListTags(); ?></div>
        </div>
        <div class="post-content">
            <?php if ($theme->hasPostThumbnail()): ?>
            <figure class="post-thumbnail">
                <?php $theme->thePostListThumbnail(); ?>
                <figcaption><?php $theme->thePostThumbnailCaption(); ?></figcaption>
            </figure>
            <?php endif; ?>
            <?php $theme->theContent(); ?>
        </div>
    </article>
    <?php if ($this->isCommentsAvailable()): ?>
        <?php $theme->theCommentsTemplate(); ?>
    <?php endif; ?>
    <?php else: ?>
    <?php $theme->theDefaultEmptyMessage(); ?>
    <?php endif; ?>
</section>
