<?php
/**
 * Elgg standard message
 * Displays a single Elgg system message
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['object'] A system message (string)
 */
?>

<p>
	<?php echo nl2br($vars['object']); ?>
</p>