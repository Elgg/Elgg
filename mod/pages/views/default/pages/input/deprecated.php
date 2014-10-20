<?php
/**
 * This view outputs the deprecated way of extending the pages form elements.
 * 
 * DON'T extend or overrule this view as it will be removed in Elgg 1.11
 * 
 * @package ElggPages
 * @deprecated Elgg 1.11
 */

$variables = elgg_get_config('pages');
if (!$variables) {
	return;
}

elgg_deprecated_notice("Using \$CONFIG->pages to extend the form elements was deprecated. 
		To extend the form please overrule the view forms/pages/edit", "1.10");

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

		echo elgg_view($input_view, array(
			'name' => $name,
			'value' => $vars[$name],
			'entity' => ($name == 'parent_guid') ? $vars['entity'] : null,
		));
	?>
</div>
<?php
}