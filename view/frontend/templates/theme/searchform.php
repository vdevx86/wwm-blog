<?php $theme = \Wwm\Blog\Cms\WordPress\ThemeFactory::getInstance(); $submitTitle = $theme->escAttrX('Search', 'submit button'); ?>
<form action="<?php $theme->printf($theme->getHomeURLNew()); ?>/" class="search-form" role="search" data-hasrequired="<?php
        $theme->_e('* Required Fields'); ?>" novalidate="novalidate" data-mage-init='{"validation":{}}'>
    <fieldset class="fieldset">
        <div class="field search required">
            <label class="label" for="s"><span class="screen-reader-text"><?php $theme->_ex('Search for:', 'label'); ?></span></label>
            <div class="control">
                <input type="search" id="s" class="input-text search-field" name="s" value="<?php $theme->printf($theme->getSearchQuery());
                    ?>" placeholder="<?php $theme->printf($theme->escAttrX('Search &hellip;', 'placeholder'));
                    ?>" data-validate="{required:true}" aria-required="true"/>
            </div>
        </div>
    </fieldset>
    <div class="actions-toolbar">
        <div class="primary">
            <button type="submit" title="<?php $theme->printf($submitTitle); ?>" class="action submit primary">
                <span><?php $theme->printf($submitTitle); ?></span>
            </button>
        </div>
    </div>
</form>
