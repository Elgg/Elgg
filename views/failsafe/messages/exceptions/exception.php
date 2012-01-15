<?php
/**
 * Elgg exception (failsafe mode)
 * Displays a single exception
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['object'] An exception
 */

?>

<p class="elgg-messages-exception">
	<span title="Unrecoverable Error">
		<?php echo elgg_echo('exception:contact_admin'); ?>
		<br /><br />
		Exception #<?php echo $vars['ts']; ?>.
	</span>
</p>
