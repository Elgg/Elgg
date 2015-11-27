<?php
/**
 * Page edit form body
 *
 * @package ElggPages
 */

$variables = elgg_get_config('pages');
$user = elgg_get_logged_in_user_entity();
$entity = elgg_extract('entity', $vars);
$can_change_access = true;
if ($user && $entity) {
	$can_change_access = ($user->isAdmin() || $user->getGUID() == $entity->owner_guid);
}

foreach ($variables as $name => $type) {
	// don't show read / write access inputs for non-owners or admin when editing
	if (($type == 'access' || $type == 'write_access') && !$can_change_access) {
		continue;
	}
	
	// don't show parent picker input for top or new pages.
	if ($name == 'parent_guid' && (!$vars['parent_guid'] || !$vars['guid'])) {
		continue;
	}

	if ($type == 'parent') {
		$input_view = "pages/input/$type";
	} else {
		$input_view = "input/$type";
	}

?>
<div>
	<label><?php echo elgg_echo("pages:$name") ?></label>
	<?php
		if ($type != 'longtext') {
			echo '<br />';
		}

		$view_vars = array(
			'name' => $name,
			'value' => $vars[$name],
			'entity' => ($name == 'parent_guid') ? $vars['entity'] : null,
		);
		if ($input_view === 'input/access' || $input_view === 'input/write_access') {
			$view_vars['entity'] = $entity;
			$view_vars['entity_type'] = 'object';
			$view_vars['entity_subtype'] = $vars['parent_guid'] ? 'page': 'page_top';

			if ($name === 'write_access_id') {
				$view_vars['purpose'] = 'write';
				if ($entity) {
					$view_vars['value'] = $entity->write_access_id;
					
					// no access change warning for write access input
					$view_vars['entity_allows_comments'] = false;
				}
			}
		}

		$output = elgg_view($input_view, $view_vars);

		if ($input_view === 'input/write_access' && strpos($output, "<!-- -->") !== 0) {
			// a dev has extended input/write_access
			elgg_deprecated_notice("The input/write_access view is deprecated. The pages plugin now uses the ['access:collections:write', 'user'] hook to alter options.", "1.11");
		}

		echo $output;
	?>
</div>
<?php
}

$cats = elgg_view('input/categories', $vars);
if (!empty($cats)) {
	echo $cats;
}


echo '<div class="elgg-foot">';
if ($vars['guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'page_guid',
		'value' => $vars['guid'],
	));
}
echo elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $vars['container_guid'],
));
if (!$vars['guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'parent_guid',
		'value' => $vars['parent_guid'],
	));
}

echo elgg_view('input/submit', array('value' => elgg_echo('save')));

echo '</div>';
