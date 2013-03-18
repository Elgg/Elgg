<?php
/**
 * Latest forum posts
 *
 * @uses $vars['entity']
 */

if ($vars['entity']->forum_enable == 'no') {
	return true;
}

$project = $vars['entity'];


$all_link = elgg_view('output/url', array(
	'href' => "discussion/owner/$project->guid",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
));

elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'projectforumtopic',
	'container_guid' => $project->getGUID(),
	'limit' => 6,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (!$content) {
	$content = '<p>' . elgg_echo('discussion:none') . '</p>';
}

$new_link = elgg_view('output/url', array(
	'href' => "discussion/add/" . $project->getGUID(),
	'text' => elgg_echo('projects:addtopic'),
	'is_trusted' => true,
));

echo elgg_view('projects/profile/module', array(
	'title' => elgg_echo('discussion:project'),
	'content' => $content,
	'all_link' => $all_link,
	'add_link' => $new_link,
));
