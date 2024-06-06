<?php

/**
 * The template for displaying the extra rewards.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/part/extra-rewards.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

$list ??= 'extraRewards';

?>

<template x-if="<?php echo esc_js($list); ?>">
    <ul class="mt-2">
        <template x-for="reward in <?php echo esc_js($list); ?>">
            <li x-text="reward"></li>
        </template>
    </ul>
</template>
