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
    $placeholder = 'Stake Address';
}

?>

<div class="input-group">
    <input x-model="address" type="text" class="form-control" placeholder="<?php echo $placeholder; ?>">
    <button class="btn btn-primary" @click="handleTracking()" x-bind:disabled="!address"><?php echo $text; ?></button>
</div>
