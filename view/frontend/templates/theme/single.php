<?php checkCompatibility(); ?>
<section id="blog" <?php self::bodyClass(); ?>>
    <?php if (self::havePosts()): self::thePost(); ?>
    <article id="post-<?php self::theId(); ?>" <?php self::postClass(); ?>>
        <div class="post-meta">
            <?php self::theDefaultAvatar(); ?>
            <div class="post-author"><strong><?php self::_e('Author'); ?>:</strong><br/><em><?php self::theAuthor(); ?></em></div>
            <div class="post-date"><strong><?php self::_e('Published'); ?>:</strong><br/><em><?php self::theDate(); ?></em></div>
        </div>
        <div class="post-links">
            <div class="post-categories"><strong><?php self::_e('Categories'); ?>:</strong> <?php self::theCategory(); ?></div>
            <div class="post-tags"><?php self::thePostListTags(); ?></div>
        </div>
        <div class="post-content">
            <?php if (self::hasPostThumbnail()): ?>
            <figure class="post-thumbnail">
                <?php self::thePostListThumbnail(); ?>
                <figcaption><?php self::thePostThumbnailCaption(); ?></figcaption>
            </figure>
            <?php endif; ?>
            <?php self::theContent(); ?>
        </div>
    </article>
    <?php if ($this->isCommentsAvailable()): ?>
        <?php self::theCommentsTemplate(); ?>
    <?php endif; ?>
    <?php else: ?>
    <?php self::theDefaultEmptyMessage(); ?>
    <?php endif; ?>
</section>
