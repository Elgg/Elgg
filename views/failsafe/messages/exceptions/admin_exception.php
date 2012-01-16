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
	<span title="<?php echo get_class($vars['object']); ?>">
	<?php

		echo nl2br($vars['object']->getMessage());

	?>
	</span>
</p>

<p class="elgg-messages-exception">
	<?php

		echo nl2br(htmlentities(print_r($vars['object'], true), ENT_QUOTES, 'UTF-8'));

	?>
</p>