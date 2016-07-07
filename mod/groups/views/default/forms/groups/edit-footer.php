<?php

/* @var ElggGroup $entity */
$entity = elgg_extract("entity", $vars, false);

?>
<div class="elgg-foot">
	<?php

	if ($entity) {
		echo elgg_view("input/hidden", array(
			"name" => "group_guid",
			"value" => $entity->getGUID(),
		));
	}

	echo elgg_view("input/submit", array("value" => elgg_echo("save")));

	if ($entity) {
		$delete_url = "action/groups/delete?guid=" . $entity->getGUID();
		echo elgg_view("output/url", array(
			"text" => elgg_echo("groups:delete"),
			"href" => $delete_url,
			"confirm" => elgg_echo("groups:deletewarning"),
			"class" => "elgg-button elgg-button-delete float-alt",
		));
	}

	?>
</div>
