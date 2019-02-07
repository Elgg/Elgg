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

if (elgg_is_admin_logged_in()) {
	echo elgg_view('messages/exceptions/admin_exception', $vars);
	return;
}

?>
<div class="elgg-messages-exception">
	<span title="Unrecoverable Error">
		<?php echo elgg_echo('exception:contact_admin'); ?>
		<br /><br />
		Exception at time <?php echo date(DATE_W3C, elgg_extract('ts', $vars)); ?>.
	</span>
</div>
