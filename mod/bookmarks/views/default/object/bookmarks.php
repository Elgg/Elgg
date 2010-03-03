<?php
/**
 * Elgg bookmark view
 * 
 * @package ElggBookmarks
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

$owner = $vars['entity']->getOwnerEntity();
$friendlytime = friendly_time($vars['entity']->time_created);
$parsed_url = parse_url($vars['entity']->address);
$faviconurl = $parsed_url['scheme'] . "://" . $parsed_url['host'] . "/favicon.ico";

//sort out the access level for display
$object_acl = get_readable_access_level($vars['entity']->access_id);
//files with these access level don't need an icon
$general_access = array('Public', 'Logged in users', 'Friends');
//set the right class for access level display - need it to set on groups and shared access only
$is_group = get_entity($vars['entity']->container_guid);
if($is_group instanceof ElggGroup){
	//get the membership type open/closed
	$membership = $is_group->membership;
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
		$access_level = "class='entity_access'";
}

if($vars['entity']->description != '')
	$view_notes = "<a class='bookmark_note' onclick=\"elgg_slide_toggle(this,'.entity_listing','.note');\">note</a>";
else
	$view_notes = '';
if (@file_exists($faviconurl)) {
	$icon = "<img src=\"{$faviconurl}\" />";
} else {
	$icon = elgg_view("profile/icon", array('entity' => $owner,'size' => 'tiny',));
}


//delete
if($vars['entity']->canEdit()){
$delete .= "<span class='delete_button'>" . elgg_view('output/confirmlink',array(	
				'href' => $vars['url'] . "action/bookmarks/delete?bookmark_guid=" . $vars['entity']->guid,
				'text' => elgg_echo("delete"),
				'confirm' => elgg_echo("bookmarks:delete:confirm"),
				)) . "</span>";
}

	$info = "<div class='entity_metadata'><table><tr><td><span {$access_level}>{$object_acl}</span></td>";

//include edit and delete options
if($vars['entity']->canEdit()){
	$info .= "<td class='entity_edit'><a href=\"{$vars['url']}pg/bookmarks/{$owner->username}/edit/{$vars['entity']->getGUID()}\">" . elgg_echo('edit') . "</a></td>";
	$info .= "<td class='entity_delete'>".$delete."</td>";  
}
	$info .= "</tr></table></div>";

$info .= "<p class='entity_title'><a href=\"{$vars['entity']->address}\">{$vars['entity']->title}</a></p>";
$info .= "<p class='entity_subtext'>Bookmarked by <a href=\"{$vars['url']}pg/bookmarks/{$owner->username}\">{$owner->name}</a> {$friendlytime} {$view_notes}</p>";

$tags = elgg_view('output/tags', array('tags' => $vars['entity']->tags));
if (!empty($tags)) {
	$info .= '<p class="tags">' . $tags . '</p>';
}
if($view_notes != ''){
	$info .= "<div class='note hidden'>". $vars['entity']->description . "</div>";
}
	
//display
echo elgg_view_listing($icon, $info);