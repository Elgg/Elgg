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

	<div class="elgg-system-message hidden radius8 error">
		<?php echo elgg_view('messages/errors/error',array('object' => $error)); ?>
	</div>
	
<?php
	}
}