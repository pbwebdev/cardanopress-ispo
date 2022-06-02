<?php

/**
 * The template for displaying the delegate section.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/delegate-section.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

$pool = cpISPO()->delegationPool();

?>

<?php cardanoPress()->template('part/delegation-details', compact('pool')); ?>

<div class="row">
    <div class="col col-sm-6">
        <?php cpISPO()->template('part/delegate-connect'); ?>
    </div>

    <div class="col col-sm-6">
        <?php cpISPO()->template('part/delegate-process'); ?>
    </div>
</div>

<div class="py-3">
    <?php cpISPO()->template('part/delegate-result'); ?>
</div>
