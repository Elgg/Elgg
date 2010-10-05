<?php
/**
 * Elgg standard message
 * Displays a single Elgg system message
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['object'] A system message (string)
 */
?>

<p>
	<?php echo nl2br($vars['object']); ?>
</p>