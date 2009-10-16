<?php
/**
 * Elgg exception
 * Displays a single exception
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['object'] An exception
 */

global $CONFIG;
?>
<!--
<?php echo get_class($vars['object']); ?>: <?php echo autop($vars['object']->getMessage()); ?>
<?php if (isset($CONFIG->debug)) { ?>
<?php
	echo print_r($vars['object'], true);
?>
<?php } ?>

-->