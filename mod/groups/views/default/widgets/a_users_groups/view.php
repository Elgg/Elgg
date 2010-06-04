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

//$groups = get_users_membership($owner);
//$groups = list_entities_from_relationship('member',$owner,false,'group','',0,$number,false,false,false);
$groups = get_entities_from_relationship('member', $owner, false, "group", "", 0, "", $number, 0, false, 0);


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
