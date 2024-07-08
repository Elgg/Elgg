<?php
/**
 * Save blog entity
 *
 * Can be called by clicking save button or preview button. If preview button,
 * we automatically save as draft. The preview button is only available for
 * non-published drafts.
 *
 * Drafts are saved with the access set to private.
 */

// save or preview
$preview = (bool) get_input('preview');

// edit or create a new entity
$new_post = true;
$revision_text = null;

$guid = (int) get_input('guid');
if (!empty($guid)) {
	$blog = get_entity($guid);
	if (!$blog instanceof \ElggBlog || !$blog->canEdit()) {
		return elgg_error_response(elgg_echo('blog:error:post_not_found'));
	}

	// save some data for revisions once we save the new edit
	$revision_text = $blog->description;
	$new_post = false;
} else {
	$blog = new \ElggBlog();
	
	$container_guid = (int) get_input('container_guid');
	$container = get_entity($container_guid);
	if (!$container || !$container->canWriteToContainer(0, 'object', 'blog')) {
		return elgg_error_response(elgg_echo('blog:error:cannot_write_to_container'));
	}
	
	$blog->container_guid = $container->guid;
}

// set the previous status for the events to update the time_created and river entries
$old_status = $blog->status;

$values = [];
$fields = elgg()->fields->get('object', 'blog');
foreach ($fields as $field) {
	$value = null;
	
	$name = elgg_extract('name', $field);
	if (elgg_extract('#type', $field) === 'tags') {
		$value = elgg_string_to_array((string) get_input($name));
	} elseif ($name === 'title') {
		$value = elgg_get_title_input();
	} else {
		$value = get_input($name);
	}
	
	if (elgg_is_empty($value) && elgg_extract('required', $field)) {
		return elgg_error_response(elgg_echo("blog:error:missing:{$name}"));
	}
	
	$values[$name] = $value;
}

// if this is a preview, force status to be draft
if ($preview) {
	$values['status'] = 'draft';
}

// if draft, set access to private and cache the future access
if ($values['status'] == 'draft') {
	$values['future_access'] = $values['access_id'];
	$values['access_id'] = ACCESS_PRIVATE;
}

// assign values to the entity
foreach ($values as $name => $value) {
	$blog->{$name} = $value;
}

if (!$blog->save()) {
	return elgg_error_response(elgg_echo('blog:error:cannot_save'));
}

// if this was an edit, create a revision annotation
if (!$new_post && $revision_text) {
	$blog->annotate('blog_revision', $revision_text);
}

$status = $blog->status;

// add to river if changing status or published, regardless of new post
// because we remove it for drafts.
if (($new_post || $old_status === 'draft') && $status === 'published') {
	elgg_create_river_item([
		'view' => 'river/object/blog/create',
		'action_type' => 'create',
		'object_guid' => $blog->guid,
		'subject_guid' => $blog->owner_guid,
		'target_guid' => $blog->container_guid,
	]);

	elgg_trigger_event('publish', 'object', $blog);

	// reset the creation time for posts that move from draft to published
	if (!$new_post) {
		$blog->time_created = time();
		$blog->save();
	}
} elseif ($old_status === 'published' && $status === 'draft') {
	elgg_delete_river([
		'object_guid' => $blog->guid,
		'action_type' => 'create',
		'limit' => false,
	]);
}

if ($blog->status == 'published' || $preview) {
	$forward_url = $blog->getURL();
} else {
	$forward_url = elgg_generate_url('edit:object:blog', [
		'guid' => $blog->guid,
	]);
}

if (get_input('header_remove')) {
	$blog->deleteIcon('header');
} else {
	$blog->saveIconFromUploadedFile('header', 'header');
}

return elgg_ok_response([
	'guid' => $blog->guid,
	'url' => $blog->getURL(),
], elgg_echo('blog:message:saved'), $forward_url);
