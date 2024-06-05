<?php

/**
 * The template for displaying the delegate process section.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/part/delegate-process.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

if (empty($text)) {
    $text = 'Delegate';
}

if (empty($textDelegated)) {
    $textDelegated = 'Delegated';
}

?>

<div class="mt-3">
    <?php if (cpISPO()->isUserDelegated()) : ?>
        <button class="btn btn-primary" type="button" disabled="true"><?php echo esc_html($textDelegated); ?></button>
    <?php else : ?>
        <template x-if="!isConnected">
            <button class="btn btn-primary" type="button" disabled="true"><?php echo esc_html($text); ?></button>
        </template>

        <template x-if="isConnected && !transactionHash">
            <button class="btn btn-primary" type="button" x-on:click="handleDelegation()" x-bind:disabled="isProcessing"><?php echo esc_html($text); ?></button>
        </template>

        <template x-if="isConnected && transactionHash">
            <button class="btn btn-primary" type="button" disabled="true"><?php echo esc_html($textDelegated); ?></button>
        </template>
    <?php endif; ?>
</div>
