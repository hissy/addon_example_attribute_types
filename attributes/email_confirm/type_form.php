<?php defined('C5_EXECUTE') or die('Access Denied.'); ?>
<fieldset>
    <legend><?php echo t('Email Options')?></legend>
    <div class="form-group">
        <?php echo $form->label('akTextPlaceholder', t('Placeholder Text'))?>
        <?php echo $form->text('akTextPlaceholder' , $akTextPlaceholder )?>
    </div>
</fieldset>