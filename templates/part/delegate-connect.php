<?php

/**
 * The template for displaying the delegate connect section.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/part/delegate-connect.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

if (empty($text)) {
    $text = 'Connect';
}

if (empty($textConnected)) {
    $textConnected = 'Connected';
}

?>


<div class="mt-3">
    <template x-if="!isConnected">
        <?php cardanoPress()->template('part/modal-trigger', compact('text')); ?>
    </template>

    <template x-if="isConnected">
        <button class="btn btn-primary" type="button" disabled="true"><?php echo esc_html($textConnected); ?></button>
    </template>
</div>
