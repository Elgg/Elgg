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
 * @uses $vars['options'] Additional options to pass to the handler with the URL query
 *                        If using custom options, make sure to impose a signed request gatekeeper in the resource view
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
$name = elgg_extract('name', $vars);

$guids = (array) elgg_extract('values', $vars, elgg_extract('value', $vars, []));

$params = elgg_extract('options', $vars, []);

$friends_only = elgg_extract('only_friends', $vars);

if ($friends_only) {
	$params['friends_only'] = true;
}

if (!empty($params)) {
	ksort($params);

	// We sign custom parameters, so that plugins can validate
	// that the request is unaltered, if needed
	$mac = elgg_build_hmac($params);
	$params['mac'] = $mac->getToken();
}

$handler = elgg_extract('handler', $vars, "livesearch");
$params['view'] = 'json'; // force json viewtype
$handler = elgg_http_add_url_query_elements($handler, $params);

$limit = (int) elgg_extract('limit', $vars, 0);

$attrs = [
	'class' => 'elgg-user-picker',
	'data-limit' => $limit,
	'data-name' => $name,
	'data-handler' => $handler,
];

?>
<div <?= elgg_format_attributes($attrs) ?>>
	<input type="text" class="elgg-input-user-picker" size="30"/>
	<?php echo elgg_view('input/hidden', ['name' => elgg_extract('name', $vars)]); ?>
	<?php
	if (!$friends_only) {
		?>
		<input type="checkbox" name="match_on" value="true"/>
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
					'input_name' => elgg_extract('name', $vars),
				]);
			}
		}
		?>
	</ul>
</div>
<script>
	require(['elgg/UserPicker'], function (UserPicker) {
		var name = <?= json_encode($name) ?>;
		UserPicker.setup('.elgg-user-picker[data-name="' + name + '"]');
	});
</script>
