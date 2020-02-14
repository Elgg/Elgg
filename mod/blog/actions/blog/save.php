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

// start a new sticky form session in case of failure
elgg_make_sticky_form('blog');

// save or preview
$save = (bool) get_input('save');

// edit or create a new entity
$guid = (int) get_input('guid');

if ($guid) {
	$entity = get_entity($guid);
	if ($entity instanceof ElggBlog && $entity->canEdit()) {
		$blog = $entity;
	} else {
		return elgg_error_response(elgg_echo('blog:error:post_not_found'));
	}

	// save some data for revisions once we save the new edit
	$revision_text = $blog->description;
	$new_post = (bool) $blog->new_post;
} else {
	$blog = new \ElggBlog();
	$new_post = true;
}

// set the previous status for the hooks to update the time_created and river entries
$old_status = $blog->status;

// set defaults and required values.
$values = [
	'title' => '',
	'description' => '',
	'status' => 'draft',
	'access_id' => ACCESS_DEFAULT,
	'comments_on' => 'On',
	'excerpt' => '',
	'tags' => '',
	'container_guid' => (int) get_input('container_guid'),
];

// fail if a required entity isn't set
$required = ['title', 'description'];

// load from POST and do sanity and access checking
foreach ($values as $name => $default) {
	if ($name === 'title') {
		$value = elgg_get_title_input();
	} else {
		$value = get_input($name, $default);
	}

	if (in_array($name, $required) && empty($value)) {
		return elgg_error_response(elgg_echo("blog:error:missing:{$name}"));
	}

	switch ($name) {
		case 'tags':
			$values[$name] = string_to_tag_array($value);
			break;

		case 'container_guid':
			// this can't be empty or saving the base entity fails
			if (!empty($value)) {
				$container = get_entity($value);
				if ($container && (!$new_post || $container->canWriteToContainer(0, 'object', 'blog'))) {
					$values[$name] = $value;
				} else {
					return elgg_error_response(elgg_echo('blog:error:cannot_write_to_container'));
				}
			} else {
				unset($values[$name]);
			}
			break;

		default:
			$values[$name] = $value;
			break;
	}
}

// if preview, force status to be draft
if (!$save) {
	$values['status'] = 'draft';
}

// if draft, set access to private and cache the future access
if ($values['status'] == 'draft') {
	$values['future_access'] = $values['access_id'];
	$values['access_id'] = ACCESS_PRIVATE;
}

// assign values to the entity
foreach ($values as $name => $value) {
	$blog->$name = $value;
}

if (!$blog->save()) {
	return elgg_error_response(elgg_echo('blog:error:cannot_save'));
}

// remove sticky form entries
elgg_clear_sticky_form('blog');

// remove autosave draft if exists
$blog->deleteAnnotations('blog_auto_save');

// no longer a brand new post.
$blog->deleteMetadata('new_post');

// if this was an edit, create a revision annotation
if (!$new_post && $revision_text) {
	$blog->annotate('blog_revision', $revision_text);
}

$status = $blog->status;

// add to river if changing status or published, regardless of new post
// because we remove it for drafts.
if (($new_post || $old_status == 'draft') && $status == 'published') {
	elgg_create_river_item([
		'view' => 'river/object/blog/create',
		'action_type' => 'create',
		'subject_guid' => $blog->owner_guid,
		'object_guid' => $blog->getGUID(),
	]);

	elgg_trigger_event('publish', 'object', $blog);

	// reset the creation time for posts that move from draft to published
	if ($guid) {
		$blog->time_created = time();
		$blog->save();
	}
} elseif ($old_status == 'published' && $status == 'draft') {
	elgg_delete_river([
		'object_guid' => $blog->guid,
		'action_type' => 'create',
		'limit' => false,
	]);
}

if ($blog->status == 'published' || !$save) {
	$forward_url = $blog->getURL();
} else {
	$forward_url = elgg_generate_url('edit:object:blog', [
		'guid' => $blog->guid,
	]);
}

return elgg_ok_response('', elgg_echo('blog:message:saved'), $forward_url);
