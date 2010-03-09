<?php

/**
 * Elgg blog listing
 */

$owner = $vars['entity']->getOwnerEntity();
$friendlytime = sprintf(elgg_echo("blog:strapline"),
									date("F j, Y",$vars['entity']->time_created)
					);
$tags = elgg_view('output/tags', array('tags' => $vars['entity']->tags));
$num_comments = elgg_count_comments($vars['entity']);
$icon = elgg_view(
		"profile/icon", array(
								'entity' => $owner,
								'size' => 'tiny',
							  )
	);
//sort out the access level for display
$object_acl = get_readable_access_level($vars['entity']->access_id);
//files with these access level don't need an icon
$general_access = array('Public', 'Logged in users', 'Friends');
//set the right class for access level display - need it to set on groups and shared access only
$check_is_group = get_entity($vars['entity']->container_guid);
if($check_is_group instanceof ElggGroup){
	//get the membership type open/closed
	$membership = $check_is_group->membership;
	//we decided to show that the item is in a group, rather than its actual access level
	$object_acl = "Group: " . $is_group->name;
	if($membership == 2)
		$access_level = "class='group_open'";
	else
		$access_level = "class='group_closed'";
}elseif($object_acl == 'Private'){
		$access_level = "class='private'";
}else{
	if(!in_array($object_acl, $general_access))
		$access_level = "class='shared_collection'";
	else
		$access_level = "class='generic_access'";
}
//display the access level
	$info = "<div class='ItemMetaData'><table><tr>";

	//$table_column_number = "";
//include edit and delete options
if ($vars['entity']->canEdit()) {
	$info .= "<td class='EditItem'><span class='EditItem'><a href=\"{$vars['url']}mod/blog/edit.php?blogpost={$vars['entity']->getGUID()}\">" . elgg_echo('edit') . "</a></span></td>";
	$info .= "<td class='DeleteItem'><div class='Delete_Button'>" . elgg_view('output/confirmlink',array('href' => $vars['url'] . "action/blog/delete?blogpost=" . $vars['entity']->getGUID(), 'text' => elgg_echo("delete"),'confirm' => elgg_echo("file:delete:confirm"),)). "</div></td>";  
	//$table_column_number = " colspan='3' ";
}

	$info .= "<td class='FavouriteItem'>" . elgg_view("blogs/options",array('entity' => $vars['entity'])) ."</td>";

$info .= "</tr></table><div><span {$access_level}>" . $object_acl . "</span></div></div>";


$info .= "<h2 class='blog_title'><a href=\"{$vars['entity']->getURL()}\">{$vars['entity']->title}</a></h2>";
$info .= "<p class='owner_timestamp'><a href=\"{$vars['url']}pg/blog/{$owner->username}\">{$owner->name}</a> {$friendlytime}, ";
$info .= "<a href='{$vars['entity']->getURL()}'>" . sprintf(elgg_echo("comments")) . " (" . $num_comments . ")</a></p>";
$info .= "<p class='blog_excerpt'>" . display_objects(strip_tags($vars['entity']->excerpt)) . "</p>";

echo elgg_view_listing($icon,$info);

