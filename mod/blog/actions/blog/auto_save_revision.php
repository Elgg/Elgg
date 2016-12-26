<?php

/**
 * Action called by AJAX periodic auto saving when editing.
 *
 * @package Blog
 */
$guid = get_input('guid');
$user = elgg_get_logged_in_user_entity();
$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
$description = get_input('description');
$excerpt = get_input('excerpt');

// because get_input() doesn't use the default if the input is ''
if (empty($excerpt)) {
	$excerpt = $description;
}

if (!$title || !$description) {
	return elgg_error_response(elgg_echo('blog:error:missing:description'));
}

if ($guid) {
	$blog = get_entity($guid);
	if (!$blog instanceof ElggBlog || !$blog->canEdit()) {
		return elgg_error_response(elgg_echo('blog:error:post_not_found'));
	}
} else {
	$blog = new ElggBlog();
	$blog->subtype = 'blog';

	// force draft and private for autosaves.
	$blog->status = ElggBlog::UNSAVED_DRAFT;
	$blog->access_id = ACCESS_PRIVATE;
	$blog->title = $title;
	$blog->description = $description;
	$blog->excerpt = elgg_get_excerpt($excerpt);

	if (!$blog->save()) {
		return elgg_error_response(elgg_echo('blog:error:cannot_save'));
	}
}

// creat draft annotation
// annotations don't have a "time_updated" so
// we have to delete everything or the times are wrong.
// don't save if nothing changed
$auto_save_annotations = $blog->getAnnotations(array(
	'annotation_name' => 'blog_auto_save',
	'limit' => 1,
		));

if ($auto_save_annotations) {
	$auto_save = array_shift($auto_save_annotations);
} else {
	$auto_save = false;
}

if (!$auto_save) {
	$annotation_id = $blog->annotate('blog_auto_save', $description);
} else if ($auto_save->value != $description) {
	$blog->deleteAnnotations('blog_auto_save');
	$annotation_id = $blog->annotate('blog_auto_save', $description);
} else {
	// this isn't an error because we have an up to date annotation.
	$annotation_id = $auto_save->id;
}

if (!$annotation_id) {
	return elgg_error_response(elgg_echo('blog:error:cannot_auto_save'));
}

return elgg_ok_response([
	'guid' => $blog->guid,
	'status_time' => date('F j, Y @ H:i', $blog->time_updated),
]);

