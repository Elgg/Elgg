<?php
/**
 * Elgg datepicker input
 * Displays a text field with a popup date picker.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] The current value, if any
 * @uses $vars['js'] Any Javascript to enter into the input tag
 * @uses $vars['internalname'] The name of the input field
 *
 */

$cal_name = sanitise_string($vars['internalname']);

if (isset($vars['class'])) {
	$class = "{$vars['class']} popup_calendar";
} else {
	$class = 'popup_calendar';
}

if ($vars['value'] > 86400) {
	//$val = date("F j, Y", $vars['value']);
	$val = date('n/d/Y', $vars['value']);
} else {
	$val = $vars['value'];
}

?>
<input type="text" name="<?php echo $vars['internalname']; ?>" value="<?php echo $val; ?>" />
<?php //@todo JS 1.8: no ?>
<script type="text/javascript">
	$(document).ready(function() {
		$('input[type=text][name=<?php echo $cal_name; ?>]').datepicker();
	});
</script>
