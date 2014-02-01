<?php

/**
 * Action for periodic auto saving of changes to the blog body via AJAX
 * For new blogs, it creates a new blog object and sets the 'status'
 * metadata to 'unsaved_draft'
 * For existing blogs, revisions are stored in 'blog_auto_save' annotation,
 * which is then deleted, when the blog is saved
 *
 * @package Blog
 */
$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
$description = get_input('description');
$excerpt = get_input('excerpt');

if (empty($title) || empty($description)) {
	register_error(elgg_echo('blog:error:missing:description'));
	forward(REFERER);
}

$guid = get_input('guid');

$container_guid = get_input('container_guid');
$container = get_entity($container_guid);

if (elgg_instanceof($container) && !$container->canWriteToContainer(0, 'object', 'blog')) {
	register_error(elgg_echo('blog:error:cannot_write_to_container'));
	forward(REFERER);
}

if ($guid) {
	$entity = get_entity($guid);

	if (!elgg_instanceof($entity, 'object', 'blog')) {
		register_error(elgg_echo('blog:error:post_not_found'));
		forward(REFERER);
	} else if (!$entity->canEdit()) {
		register_error(elgg_echo('blog:error:cannot_edit_post'));
		forward(REFERER);
	}
	$blog = $entity;
} else {

	$blog = new ElggBlog();
	$blog->subtype = 'blog';

	if (elgg_instanceof($container)) {
		$blog->container_guid = $container->getGUID();
	}

	// force draft and private for autosaves.
	$blog->status = 'unsaved_draft';
	$blog->access_id = ACCESS_PRIVATE;
	$blog->title = $title;
	$blog->description = $description;

	if (empty($excerpt)) {
		$excerpt = $description;
	}
	$blog->excerpt = elgg_get_excerpt($excerpt);

	// mark this as a brand new post so we can work out the
	// river / revision logic in the real save action.
	$blog->new_post = TRUE;

	if (!$blog->save()) {
		register_error(elgg_echo('blog:error:cannot_save'));
		forward(REFERER);
	}
}

// create auto save annotation
// annotations don't have a "time_updated" so
// we have to delete everything or the times are wrong.
// don't save if nothing changed
$auto_save_annotations = $blog->getAnnotations(array(
	'annotation_name' => 'blog_auto_save',
	'limit' => 1,
		));

$old_description = '';
$annotation_id = false;
if ($auto_save_annotations) {
	$last_auto_save_annotation = $auto_save_annotations[0];
	$annotation_id = $last_auto_save_annotation->id;
	$old_description = $last_auto_save_annotation->value;
}

if ($old_description != $description) {
	if ($annotation_id) {
		$blog->deleteAnnotations('blog_auto_save');
	}
	$annotation_id = $blog->annotate('blog_auto_save', $description);
}

if (!$annotation_id) {
	register_error(elgg_echo('blog:error:cannot_auto_save'));
	forward(REFERER);
}

$json = array(
	'guid' => $blog->getGUID(),
	'msg' => date('F j, Y @ H:i:s', time())
);
echo json_encode($json);
forward($blog->getURL());
