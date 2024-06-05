<?php

/**
 * The template for displaying the track rewards process.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/part/track-process.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

if (empty($text)) {
    $text = 'Track';
}

if (empty($placeholder)) {
    $placeholder = 'e.g. stake1u8x94....';
}

?>

<div class="input-group">
    <input x-model="address" type="text" class="form-control" placeholder="<?php echo esc_attr($placeholder); ?>">
    <button class="btn btn-primary" x-on:click="handleTracking()" x-bind:disabled="!address"><?php echo esc_html($text); ?></button>
</div>
