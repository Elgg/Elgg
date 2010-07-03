<?php

/** 
 *  Group profile widget - this displays a user's groups on their profile
 **/

//the number of groups to display
$number = (int) $vars['entity']->num_display;
if (!$number) {
	$number = 4;
}

$options = array(
	'relationship' => 'member',
	'relationship_guid' => $vars['entity']->owner_guid,
	'types' => 'group',
	'limit' => $number,
);


$groups = elgg_get_entities_from_relationship($options);

if ($groups) {

	echo "<div class=\"groupmembershipwidget\">";

	foreach ($groups as $group) {
		$icon = elgg_view(
				"groups/icon", array(
				'entity' => $group,
				'size' => 'small',
				)
		);

		$group_link = $group->getURL();

		echo <<<___END

<div class="contentWrapper">
	$icon
	<div class="search_listing_info">
		<p>
			<span><a href="$group_link">$group->name</a></span><br />
			$group->briefdescription
		</p>
	</div>
	<div class="clearfloat"></div>
</div>
___END;

	}
	echo "</div>";
}
