<?php
/**
 * Elgg groups plugin display topic posts
 */
/*
// set up breadcrumbs
$group_guid = get_input('group_guid');
$group = get_entity($group_guid);
elgg_push_breadcrumb(elgg_echo('groups'), elgg_get_site_url()."pg/groups/world/");
elgg_push_breadcrumb($group->name, $group->getURL());
elgg_push_breadcrumb(elgg_echo('item:object:groupforumtopic'), elgg_get_site_url()."pg/groups/forum/{$vars['entity']->container_guid}");
elgg_push_breadcrumb($vars['entity']->title);

echo elgg_view('navigation/breadcrumbs');
*/

//display follow up comments
$count = $vars['entity']->countAnnotations('group_topic_post');
$offset = (int) get_input('offset',0);

$baseurl = elgg_get_site_url() . "mod/groups/topicposts.php?topic={$vars['entity']->guid}&group_guid={$vars['entity']->container_guid}";
echo elgg_view('navigation/pagination',array(
												'limit' => 50,
												'offset' => $offset,
												'baseurl' => $baseurl,
												'count' => $count,
											));

?>
<?php
	//display the topic
	echo elgg_view("forum/maintopic",array('entity' => $vars['entity']));

	// check to find out the status of the topic and act
	if($vars['entity']->status == "closed") {
		echo elgg_view_comments($vars['entity'], false);
		//this topic has been closed by the owner
		echo "<h3>" . elgg_echo("groups:topicisclosed") . "</h3>";
		echo "<p>" . elgg_echo("groups:topiccloseddesc") . "</p>";

	}elseif(elgg_get_page_owner()->isMember(get_loggedin_user())){
		//comments are on and the user viewing is a member
		echo elgg_view_comments($vars['entity']);
	}else{
		//the user is not a member so cannot post a comment
		echo elgg_view_comments($vars['entity'], false);
	}
