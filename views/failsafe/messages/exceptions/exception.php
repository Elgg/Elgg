<?php
/**
 * Elgg exception (failsafe mode)
 * Displays a single exception
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['object'] An exception
 */

?>

<p class="messages_exception">
	<span title="<?php echo get_class($vars['object']); ?>">
	<?php

		echo nl2br($vars['object']->getMessage());

	?>
	</span>
</p>

<?php
global $CONFIG;
if (isset($CONFIG->debug)) {
?>

<p class="messages_exception">
	<?php

		echo nl2br(htmlentities(print_r($vars['object'], true), ENT_QUOTES, 'UTF-8'));

	?>
</p>
<?php

}
