<?php

/**
 * Group edit form
 *
 * This view contains everything related to group access.
 * eg: how can people join this group, who can see the group, etc
 *
 * @package ElggGroups
 */

$entity = elgg_extract("entity", $vars, false);
$membership = elgg_extract("membership", $vars);
$visibility = elgg_extract("vis", $vars);

if ($entity) {
	$owner_guid = $entity->getOwnerGUID();
	$content_access_mode = $entity->getContentAccessMode();
} else {
	$content_access_mode = ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED;
}

?>
<div>
	<label>
		<?php echo elgg_echo("groups:membership"); ?><br />
		<?php echo elgg_view("input/select", array(
			"name" => "membership",
			"value" => $membership,
			"options_values" => array(
				ACCESS_PRIVATE => elgg_echo("groups:access:private"),
				ACCESS_PUBLIC => elgg_echo("groups:access:public"),
			)
		));
		?>
	</label>
</div>

<div>
	<label>
		<?php echo elgg_echo("groups:content_access_mode"); ?><br />
		<?php echo elgg_view("input/select", array(
			"name" => "content_access_mode",
			"value" => $content_access_mode,
			"options_values" => array(
				ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED => elgg_echo("groups:content_access_mode:unrestricted"),
				ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY => elgg_echo("groups:content_access_mode:membersonly"),
			),
		));
		?>
	</label>
</div>
	
<?php

if (elgg_get_plugin_setting("hidden_groups", "groups") == "yes") {
	$access_options = array(
		ACCESS_PRIVATE => elgg_echo("groups:access:group"),
		ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
		ACCESS_PUBLIC => elgg_echo("PUBLIC"),
	);
?>

<div>
	<label>
			<?php echo elgg_echo("groups:visibility"); ?><br />
			<?php echo elgg_view("input/access", array(
				"name" => "vis",
				"value" =>  $visibility,
				"options_values" => $access_options,
			));
			?>
	</label>
</div>

<?php
}

if ($entity && ($owner_guid == elgg_get_logged_in_user_guid() || elgg_is_admin_logged_in())) {
	$members = array();

	$options = array(
		"relationship" => "member",
		"relationship_guid" => $entity->getGUID(),
		"inverse_relationship" => true,
		"type" => "user",
		"limit" => 0,
	);

	$batch = new ElggBatch("elgg_get_entities_from_relationship", $options);
	foreach ($batch as $member) {
		$option_text = "$member->name (@$member->username)";
		$members[$member->guid] = htmlspecialchars($option_text, ENT_QUOTES, "UTF-8", false);
	}
?>

<div>
	<label>
			<?php echo elgg_echo("groups:owner"); ?><br />
			<?php echo elgg_view("input/select", array(
				"name" => "owner_guid",
				"value" =>  $owner_guid,
				"options_values" => $members,
				"class" => "groups-owner-input",
			));
			?>
	</label>
	<?php
	if ($owner_guid == elgg_get_logged_in_user_guid()) {
		echo "<span class='elgg-text-help'>" . elgg_echo("groups:owner:warning") . "</span>";
	}
	?>
</div>

<?php
}
