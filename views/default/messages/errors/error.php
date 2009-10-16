<?php
/**
 * Elgg error message
 * Displays a single error message
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['object'] An error message (string)
 */
?>

<p>
	<?php echo elgg_view('output/longtext', array('value' => $vars['object'])); ?>
</p>