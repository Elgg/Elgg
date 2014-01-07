<?php

/**
 * Group edit form
 *
 * This view contains the group profile field configuration
 *
 * @package ElggGroups
 */

$name = elgg_extract("name", $vars);
$group_profile_fields = elgg_get_config("group");

?>
<div>
<label><?php echo elgg_echo("groups:icon"); ?></label><br />
	<?php echo elgg_view("input/file", array("name" => "icon")); ?>
</div>
<div>
	<label><?php echo elgg_echo("groups:name"); ?></label><br />
	<?php echo elgg_view("input/text", array(
		"name" => "name",
		"value" => $name,
	));
	?>
</div>
<?php

// show the configured group profile fields
foreach ((array)$group_profile_fields as $shortname => $valtype) {
	if ($valtype == "hidden") {
		echo elgg_view("input/{$valtype}", array(
			"name" => $shortname,
			"value" => elgg_extract($shortname, $vars),
		));
		continue;
	}

	$line_break = ($valtype == "longtext") ? "" : "<br />";
	$label = elgg_echo("groups:{$shortname}");
	$input = elgg_view("input/{$valtype}", array(
		"name" => $shortname,
		"value" => elgg_extract($shortname, $vars),
	));

	echo "<div><label>{$label}</label>{$line_break}{$input}</div>";
}