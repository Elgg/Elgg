<?php
/**
 * Edit blog form
 *
 * @package Blog
 */

$draft_warning = $vars['draft_warning'];
if ($draft_warning) {
	$draft_warning = '<span class="message warning">' . $draft_warning . '</span>';
}

$action_buttons = '';
$delete_link = '';

if ($vars['guid']) {
	// add a delete button if editing
	$delete_url = "action/blog/delete?guid={$vars['guid']}";
	$delete_link = elgg_view('output/confirmlink', array(
		'href' => $delete_url,
		'text' => elgg_echo('delete'),
		'class' => 'elgg-action-button disabled'
	));
}

$save_button = elgg_view('input/submit', array('value' => elgg_echo('save')));
$action_buttons = $save_button . $delete_link;

$title_label = elgg_echo('title');
$title_input = elgg_view('input/text', array(
	'internalname' => 'title',
	'internalid' => 'blog_title',
	'value' => $vars['title']
));

$excerpt_label = elgg_echo('blog:excerpt');
$excerpt_input = elgg_view('input/text', array(
	'internalname' => 'excerpt',
	'internalid' => 'blog_excerpt',
	'value' => html_entity_decode($vars['excerpt'], ENT_COMPAT, 'UTF-8')
));

$body_label = elgg_echo('blog:body');
$body_input = elgg_view('input/longtext', array(
	'internalname' => 'description',
	'internalid' => 'blog_description',
	'value' => $vars['description']
));

$save_status = elgg_echo('blog:save_status');
if ($vars['publish_date']) {
	$saved = date('F j, Y @ H:i', $vars['publish_date']);
} else {
	$saved = elgg_echo('blog:never');
}

$status_label = elgg_echo('blog:status');
$status_input = elgg_view('input/pulldown', array(
	'internalname' => 'status',
	'internalid' => 'blog_status',
	'value' => $vars['status'],
	'options_values' => array(
		'draft' => elgg_echo('blog:status:draft'),
		'published' => elgg_echo('blog:status:published')
	)
));

$comments_label = elgg_echo('comments');
$comments_input = elgg_view('input/pulldown', array(
	'internalname' => 'comments_on',
	'internalid' => 'blog_comments_on',
	'value' => $vars['comments_on'],
	'options_values' => array('On' => elgg_echo('on'), 'Off' => elgg_echo('off'))
));

$tags_label = elgg_echo('tags');
$tags_input = elgg_view('input/tags', array(
	'internalname' => 'tags',
	'internalid' => 'blog_tags',
	'value' => $vars['tags']
));

$access_label = elgg_echo('access');
$access_input = elgg_view('input/access', array(
	'internalname' => 'access_id',
	'internalid' => 'blog_access_id',
	'value' => $vars['access_id']
));

// not being used
$publish_date_label = elgg_echo('blog:publish_date');
$publish_date_input = elgg_view('input/datetime', array(
	'internalname' => 'publish_date',
	'internalid' => 'blog_publish_date',
	'value' => $vars['publish_date']
));

$categories_input = elgg_view('categories', $vars);

// hidden inputs
$container_guid_input = elgg_view('input/hidden', array('internalname' => 'container_guid', 'value' => elgg_get_page_owner_guid()));
$guid_input = elgg_view('input/hidden', array('internalname' => 'guid', 'value' => $vars['guid']));


echo <<<___HTML

$draft_warning

<p class="mtm">
	<label for="blog_title">$title_label</label>
	$title_input
</p>

<p>
	<label for="blog_excerpt">$excerpt_label</label>
	$excerpt_input
</p>

<label for="blog_description">$body_label</label>
$body_input
<br />

<p>
	<label for="blog_tags">$tags_label</label>
	$tags_input
</p>

<p>
	<label for="blog_comments_on">$comments_label</label>
	$comments_input
</p>

<p>
	<label for="blog_access_id">$access_label</label>
	$access_input
</p>

<p>
	<label for="blog_status">$status_label</label>
	$status_input
</p>

$categories_input

<p class="elgg-subtext ptm pbm mbn elgg_hrt">
	$save_status <span class="blog-save-status-time">$saved</span>
</p>

$guid_input
$container_guid_input

$action_buttons

___HTML;
