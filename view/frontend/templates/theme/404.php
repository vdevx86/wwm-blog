<?php $theme = \Wwm\Blog\Cms\WordPress\ThemeFactory::getInstance(); ?>
<section id="blog" <?php $theme->bodyClass(); ?>>
    <article>
        <div class="post-content">
            <div class="message error empty">
                <div><?php $theme->_e('Nothing was found at this location. Maybe try a search?'); ?></div>
            </div>
        </div>
    </article>
</section>
