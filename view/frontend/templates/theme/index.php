<?php checkCompatibility(); global $theme; ?>
<section id="blog" <?php $theme->bodyClass(); ?>>
    <?php if ($theme->havePosts()): ?>
    <ol class="post-items items list">
        <?php $I = 0; while ($theme->havePosts()): $theme->thePost(); ++$I; ?>
        <li class="<?php $theme->thePostListClass($I); ?>">
            <article id="post-<?php $theme->theId(); ?>" <?php $theme->postClass(); ?>>
                <h2 class="post-title">
                    <a href="<?php $theme->thePermalink(); ?>" class="post-title-link"><?php $theme->theTitle(); ?></a>
                </h2>
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
                    <?php $theme->theExcerpt(); ?>
                </div>
                <div class="post-actions"><?php $theme->thePrimaryButton(); ?></div>
            </article>
        </li>
        <?php endwhile; ?>
    </ol>
    <?php $theme->thePostsPagination(); ?>
    <?php else: ?>
    <?php $theme->theDefaultEmptyMessage(); ?>
    <?php endif; ?>
</section>
