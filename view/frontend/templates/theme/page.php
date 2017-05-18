<?php checkCompatibility(); ?>
<section id="blog" <?php self::bodyClass(); ?>>
    <?php if (self::havePosts()): self::thePost(); ?>
    <article id="post-<?php self::theId(); ?>" <?php self::postClass(); ?>>
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
