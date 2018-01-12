<?php
/**
 * Elgg exception (failsafe mode)
 * Displays a single exception
 *
 * @tip Enable "developers" to give admins a stacktrace view.
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
		Exception at time <?php echo elgg_extract('ts', $vars); ?>.
	</span>
</p>
