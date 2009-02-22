<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	//$url = $object->getURL();
	$forumtopic = $object->guid;
	$group_guid = $object->container_guid;
	
	$url = $vars['url'] . "mod/groups/topicposts.php?topic=" . $forumtopic . "&group_guid=" . $group_guid;
	
	$url_user = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("groupforum:river:posted"),$url_user) . " ";
	$string .= elgg_echo("groupforum:river:annotate:create") . " | <a href=\"" . $url . "\">" . $object->title . "</a>";
	
?>

<?php echo $string; ?>