<?php
/**
 * Elgg exception
 * Displays a single exception
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['object'] An exception
 */

?>
<!--
<?php echo get_class($vars['object']); ?>: <?php echo elgg_autop($vars['object']->getMessage()); ?>


<?php if (elgg_get_config('debug')) { ?>
<?php

		echo print_r($vars['object'], true);

	?>
<?php } ?>

-->