<?php
/**
 * Elgg list errors
 * Lists error messages
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 * @uses $vars['object'] An array of error messages
 */

if (!empty($vars['object']) && is_array($vars['object'])) {

?>

	<div class="messages error">

<?php
		foreach ($vars['object'] as $error) {
			echo elgg_view('messages/errors/error', array('object' => $error));
		}
?>
	</div>
<?php
}