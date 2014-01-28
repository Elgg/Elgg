<?php
/**
 * Edit blog form
 *
 * @package Blog
 */

$blog = get_entity($vars['guid']);
$vars['entity'] = $blog;

$draft_warning = $vars['draft_warning'];
if ($draft_warning) {
	$draft_warning = '<span class="mbm elgg-text-help">' . $draft_warning . '</span>';
}

$action_buttons = '';
$delete_link = '';
$preview_button = '';

if ($vars['guid']) {
	// add a delete button if editing
	$delete_url = "action/blog/delete?guid={$vars['guid']}";
	$delete_link = elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => elgg_echo('delete'),
		'class' => 'elgg-button elgg-button-delete float-alt'
	));
}

// published blogs do not get the preview button
if (!$vars['guid'] || ($blog && $blog->status != 'published')) {
	$preview_button = elgg_view('input/submit', array(
		'value' => elgg_echo('preview'),
		'name' => 'preview',
		'class' => 'mls',
	));
}

$save_button = elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
	'name' => 'save',
));
$action_buttons = $save_button . $preview_button . $delete_link;

$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'name' => 'title',
	'id' => 'blog-title',
	'value' => $vars['title']
));

$excerpt_label = elgg_echo('blog:excerpt');
$excerpt_input = elgg_view('input/text', array(
	'name' => 'excerpt',
	'id' => 'blog-excerpt',
	'value' => _elgg_html_decode($vars['excerpt'])
));

$body_label = elgg_echo('blog:body');
$body_input = elgg_view('input/longtext', array(
	'name' => 'description',
	'id' => 'blog-description',
	'value' => $vars['description']
));

$save_status = elgg_echo('blog:save_status');
if ($vars['guid']) {
	$entity = get_entity($vars['guid']);
	$saved = date('F j, Y @ H:i', $entity->time_created);
} else {
	$saved = elgg_echo('never');
}

$status_label = elgg_echo('status');
$status_input = elgg_view('input/select', array(
	'name' => 'status',
	'id' => 'blog-status',
	'value' => $vars['status'],
	'options_values' => array(
		'draft' => elgg_echo('status:draft'),
		'published' => elgg_echo('status:published')
	)
));

$comments_label = elgg_echo('comments');
$comments_input = elgg_view('input/select', array(
	'name' => 'comments_on',
	'id' => 'blog-comments-on',
	'value' => $vars['comments_on'],
	'options_values' => array('On' => elgg_echo('on'), 'Off' => elgg_echo('off'))
));

$tags_label = elgg_echo('tags');
$tags_input = elgg_view('input/tags', array(
	'name' => 'tags',
	'id' => 'blog-tags',
	'value' => $vars['tags']
));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array(
	'name' => 'access_id',
	'id' => 'blog-access-id',
	'value' => $vars['access_id']
));

$categories_input = elgg_view('input/categories', $vars);

// hidden inputs
$container_guid_input = elgg_view('input/hidden', array('name' => 'container_guid', 'value' => elgg_get_page_owner_guid()));
$guid_input = elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['guid']));


echo <<<___HTML

$draft_warning

<div>
	<label for="blog-title">$title_label</label>
	$title_input
</div>

<div>
	<label for="blog-excerpt">$excerpt_label</label>
	$excerpt_input
</div>

<div>
	<label for="blog-description">$body_label</label>
	$body_input
</div>

<div>
	<label for="blog-tags">$tags_label</label>
	$tags_input
</div>

$categories_input

<div>
	<label for="blog-comments-on">$comments_label</label>
	$comments_input
</div>

<div>
	<label for="blog-access-id">$access_label</label>
	$access_input
</div>

<div>
	<label for="blog-status">$status_label</label>
	$status_input
</div>

<div class="elgg-foot">
	<div class="elgg-subtext mbm">
	$save_status <span class="blog-save-status-time">$saved</span>
	</div>

	$guid_input
	$container_guid_input

	$action_buttons
</div>

___HTML;
