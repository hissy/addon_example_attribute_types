<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<fieldset>
    <legend><?=t('I Agree to Terms Options')?></legend>

    <div class="form-group">
        <label class="control-label" for="akCheckedByDefault"><?=t("Default Value")?></label>
        <div class="checkbox"><label><?=$form->checkbox('akCheckedByDefault', 1, $akCheckedByDefault)?> <?=t('The checkbox will be checked by default.')?></label>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label" for="akLabelHTML"><?=t("Label HTML")?></label>
        <?=$editor->outputStandardEditor('akLabelHTML', $akLabelHTML)?>
    </div>

</fieldset>