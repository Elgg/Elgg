<?php
/**
 * User Picker.  Sends an array of user guids.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['value'] Array of user guids for already selected users or null
 * @uses $vars['name']  The name of the input field
 *
 *
 * Defaults to lazy load user lists in paginated alphabetical order. User needs
 * two type two characters before seeing the user popup list.
 *
 * As users are checked they move down to a "users" box.
 * When this happens, a hidden input is created also.
 * 	{$internalnal}[] with the value the GUID.
 *
 * @warning: this is not stable
 */

elgg_load_js('elgg.userpicker');

function user_picker_add_user($user_id) {
	$user = get_entity($user_id);
	if (!$user || !($user instanceof ElggUser)) {
		return FALSE;
	}
	
	$icon = $user->getIconURL('tiny');
	
	$code = '<li class="elgg-image-block">';
	$code .= "<div class='elgg-image'><img class=\"livesearch_icon\" src=\"$icon\" /></div>";
	$code .= "<div class='elgg-image-alt'><a onclick='elgg.userpicker.removeUser(this, $user_id)'><strong>X</strong></a></div>";
	$code .= "<div class='elgg-body'>";
	$code .= "$user->name - $user->username";
	$code .= "<input type=\"hidden\" name=\"members[]\" value=\"$user_id\">";
	$code .= "</div>";
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
	<label><input type="checkbox" name="match_on" value="true" /><?php echo elgg_echo('userpicker:only_friends'); ?></label>
	<ul class="elgg-user-picker-entries"><?php echo $user_list; ?></ul>
</div>
<script type="text/javascript">
	elgg.userpicker.userList = <?php echo $json_values ?>;
</script>