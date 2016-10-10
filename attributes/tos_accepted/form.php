<?php
defined('C5_EXECUTE') or die("Access Denied.");
$label = isset($label) ? $label : $controller->getAttributeKey()->getAttributeKeyDisplayName();
?>
<div class="checkbox tos">
    <label>
        <input
            type="checkbox"
            value="1"
            name="<?=$view->field('value')?>"
            <?php if ($checked) { ?> checked <?php } ?>
        >
        <?=htmLawed($label, ['safe' => 1])?>
    </label>
</div>