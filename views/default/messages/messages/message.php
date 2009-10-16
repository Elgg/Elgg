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
	<?php echo elgg_view('output/longtext', array('value' => $vars['object'])); ?>
</p>