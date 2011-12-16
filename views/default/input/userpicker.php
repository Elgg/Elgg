<?php
/**
 * User Picker.  Sends an array of user guids.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] Array of user guids for already selected users or null
 *
 * The name of the hidden fields is members[]
 *
 * @warning Only a single input/userpicker is supported per web page.
 *
 * Defaults to lazy load user lists in alphabetical order. User needs
 * to type two characters before seeing the user popup list.
 *
 * As users are selected they move down to a "users" box.
 * When this happens, a hidden input is created with the
 * name of members[] and a value of the GUID.
 */

elgg_load_js('elgg.userpicker');
elgg_load_js('jquery.ui.autocomplete.html');

function user_picker_add_user($user_id) {
	$user = get_entity($user_id);
	if (!$user || !($user instanceof ElggUser)) {
		return false;
	}
	
	$icon = elgg_view_entity_icon($user, 'tiny', array('use_hover' => false));

	// this html must be synced with the userpicker.js library
	$code = '<li><div class="elgg-image-block">';
	$code .= "<div class='elgg-image'>$icon</div>";
	$code .= "<div class='elgg-image-alt'><a href='#' class='elgg-userpicker-remove'>X</a></div>";
	$code .= "<div class='elgg-body'>" . $user->name . "</div>";
	$code .= "</div>";
	$code .= "<input type=\"hidden\" name=\"members[]\" value=\"$user_id\">";
	$code .= '</li>';
	
	return $code;
}

// loop over all values and prepare them so that "in" will work in javascript
$values = array();
if (!is_array($vars['value'])) {
	$vars['value'] = array($vars['value']);
}
foreach ($vars['value'] as $value) {
	$values[$value] = TRUE;
}

// convert the values to a json-encoded list
$json_values = json_encode($values);

// create an HTML list of users
$user_list = '';
foreach ($vars['value'] as $user_id) {
	$user_list .= user_picker_add_user($user_id);
}

?>
<div class="elgg-user-picker">
	<input type="text" class="elgg-input-user-picker" size="30"/>
	<input type="checkbox" name="match_on" value="true" />
	<label><?php echo elgg_echo('userpicker:only_friends'); ?></label>
	<ul class="elgg-user-picker-list"><?php echo $user_list; ?></ul>
</div>
<script type="text/javascript">
	// @todo grab the values in the init function rather than using inline JS
	elgg.userpicker.userList = <?php echo $json_values ?>;
</script>