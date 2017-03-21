<?php
/**
 * User Picker.  Sends an array of user guids.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['values'] Array of user guids for already selected users or null
 * @uses $vars['limit'] Limit number of users (default 0 = no limit)
 * @uses $vars['name'] Name of the returned data array (default "members")
 * @uses $vars['handler'] Name of page handler used to power search (default "livesearch")
 * @uses $vars['only_friends'] If enabled, will turn the input into a friends picker
 *
 * Defaults to lazy load user lists in alphabetical order. User needs
 * to type two characters before seeing the user popup list.
 *
 * As users are selected they move down to a "users" box.
 * When this happens, a hidden input is created to return the GUID in the array with the form
 */
if (empty($vars['name'])) {
	$vars['name'] = 'members';
}
$name = $vars['name'];
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

$guids = (array) elgg_extract('values', $vars, []);

$handler = elgg_extract('handler', $vars, 'livesearch');
$handler = htmlspecialchars($handler, ENT_QUOTES, 'UTF-8');

$limit = (int) elgg_extract('limit', $vars, 0);
?>
<div class="elgg-user-picker" data-limit="<?php echo $limit ?>" data-name="<?php echo $name ?>" data-handler="<?php echo $handler ?>">
	<input type="text" class="elgg-input-user-picker" size="30"/>
	<?php echo elgg_view('input/hidden', ['name' => $vars['name']]); ?>
	<?php
	if (!elgg_extract('only_friends', $vars, false)) {
		?>
		<input type="checkbox" name="match_on" value="true" />
		<label><?php echo elgg_echo('userpicker:only_friends'); ?></label>
		<?php
	}
	?>
	<ul class="elgg-list elgg-user-picker-list">
		<?php
		foreach ($guids as $guid) {
			$entity = get_entity($guid);
			if ($entity) {
				echo elgg_view('input/userpicker/item', [
					'entity' => $entity,
					'input_name' => $vars['name'],
				]);
			}
		}
		?>
	</ul>
</div>
<script>
	require(['elgg/UserPicker'], function (UserPicker) {
		UserPicker.setup('.elgg-user-picker[data-name="<?php echo $name ?>"]');
	});
</script>
