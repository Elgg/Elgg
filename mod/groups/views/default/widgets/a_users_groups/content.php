<?php

/** 
 *  Group profile widget - this displays a users groups on their profile
 **/

//the number of groups to display
$number = (int) $vars['entity']->num_display;
if (!$number) {
	$number = 4;
}

//the page owner
$owner = $vars['entity']->owner_guid;

$groups = elgg_get_entities_from_relationship(array(
	'relationship' => 'member',
	'relationship_guid' => $owner,
	'types' => 'group',
	'limit' => $number,
));


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
	<div class="clearfix"></div>
</div>
___END;

	}
	echo "</div>";
}
