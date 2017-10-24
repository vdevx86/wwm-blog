<?php $theme = \Wwm\Blog\Cms\WordPress\ThemeFactory::getInstance(); if ($theme->postPasswordRequired()) return; ?>
<section id="comments" class="comments-area">
    <?php if ($theme->isCommentsAvailable()): ?>
        <?php if ($theme->hasComments()): ?>
            <header class="comments-title"><?php $theme->theCommentsTitle(); ?></header>
            <ol class="commentlist"><?php $theme->wpListComments(); ?></ol>
            <?php $theme->theCommentsNavigation(); ?>
        <?php endif; ?>
        <?php $theme->theCommentForm(); ?>
    <?php else: ?>
    <strong class="nocomments"><?php $theme->_e('Comments are closed.'); ?></strong>
    <?php endif; ?>
</section>
