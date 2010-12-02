<?php
/**
 * Elgg list errors
 * Lists error messages
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['object'] An array of error messages
 */

if (!empty($vars['object']) && is_array($vars['object'])) {
	foreach($vars['object'] as $error) {
?>

	<li class="elgg-state-error radius8">
		<?php echo elgg_view('messages/errors/error', array('object' => $error)); ?>
	</li>
	
<?php
	}
}