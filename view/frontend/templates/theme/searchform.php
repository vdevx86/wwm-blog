<?php global $theme; $submitTitle = $theme->escAttrX('Search', 'submit button'); ?>
<form action="<?php echo $theme->getHomeURLNew(); ?>/" class="search-form" role="search" data-hasrequired="<?php $theme->_e('* Required Fields'); ?>" novalidate="novalidate" data-mage-init='{"validation":{}}'>
    <fieldset class="fieldset">
        <div class="field search required">
            <label class="label" for="s"><span class="screen-reader-text"><?php echo $theme->_x('Search for:', 'label'); ?></span></label>
            <div class="control">
                <input type="search" id="s" class="input-text search-field" name="s" value="<?php echo $theme->getSearchQuery(); ?>" placeholder="<?php echo $theme->escAttrX('Search &hellip;', 'placeholder'); ?>" data-validate="{required:true}" aria-required="true"/>
            </div>
        </div>
    </fieldset>
    <div class="actions-toolbar">
        <div class="primary">
            <button type="submit" title="<?php echo $submitTitle; ?>" class="action submit primary"><span><?php echo $submitTitle; ?></span></button>
        </div>
    </div>
</form>
