<?php /* @codingStandardsIgnoreFile */ global $theme; ?>
<section id="blog" <?php $theme->bodyClass(); ?>>
    <?php if ($theme->havePosts()): $theme->thePost(); ?>
    <article id="post-<?php $theme->theId(); ?>" <?php $theme->postClass(); ?>>
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
