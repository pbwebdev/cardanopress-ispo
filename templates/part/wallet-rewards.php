<?php

/**
 * The template for displaying the connected wallet rewards.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/part/wallet-rewards.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

use CardanoPress\Helpers\NumberHelper;

if (empty($text)) {
    $text = 'Connect wallet';
}

if (empty($rewards)) {
    $rewards = cpISPO()->userProfile()->getCalculatedRewards();
}

$rewards = NumberHelper::adaPrecision($rewards);

?>

<template x-if="!isConnected">
    <?php cardanoPress()->template('part/modal-trigger', compact('text')); ?>
</template>

<template x-if="isConnected">
    <input value="<?php echo esc_attr($rewards); ?>" type="text" class="form-control text-center" readonly disabled>
</template>

<?php cpISPO()->template('part/extra-rewards', ['list' => json_encode(cpISPO()->userProfile()->getExtraRewards())]); ?>
