<?php
/**
 * Elgg list system messages
 * Lists system messages
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['object'] An array of system messages
 */

if (!empty($vars['object']) && is_array($vars['object'])) {
	foreach($vars['object'] as $message) {
?>

	<li class="elgg-state-success radius8">
		<?php echo elgg_view('messages/messages/message',array('object' => $message)); ?>
	</li>

<?php
	}
}
