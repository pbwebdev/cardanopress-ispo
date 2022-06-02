<?php

/**
 * The template for displaying the estimate section.
 *
 * This can be overridden by copying it to yourtheme/cardanopress/ispo/estimate-section.php.
 *
 * @package ThemePlate
 * @since   0.1.0
 */

?>

<div class="col-md-8 mx-auto">
    <div class="row align-items-center">
        <div class="col-8 p-3">
            <div class="card p-3">
                <p>Use the sliders to change the values to estimate your potential rewards.</p>

                <?php cpISPO()->template('part/estimate-calculator'); ?>
            </div>
        </div>

        <div class="col p-3">
            <?php cpISPO()->template('part/estimate-result'); ?>
        </div>
    </div>
</div>
