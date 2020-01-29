<?php
/**
 * Holds helper functions for blog plugin
 */

/**
 * Pull together blog variables for the save form
 *
 * @param ElggBlog       $post     blog post being edited
 * @param ElggAnnotation $revision a revision from which to edit
 * @return array
 */
function blog_prepare_form_vars($post = null, $revision = null) {

	// input names => defaults
	$values = [
		'title' => null,
		'description' => null,
		'status' => 'published',
		'access_id' => ACCESS_DEFAULT,
		'comments_on' => 'On',
		'excerpt' => null,
		'tags' => null,
		'container_guid' => null,
		'guid' => null,
		'entity' => $post,
		'draft_warning' => '',
	];

	if ($post) {
		foreach (array_keys($values) as $field) {
			if (isset($post->$field)) {
				$values[$field] = $post->$field;
			}
		}

		if ($post->status == 'draft') {
			$values['access_id'] = $post->future_access;
		}
	}

	if (elgg_is_sticky_form('blog')) {
		$sticky_values = elgg_get_sticky_values('blog');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	$params = ['entity' => $post];
	$values = elgg_trigger_plugin_hook('form:values', 'blog', $params, $values);

	elgg_clear_sticky_form('blog');

	if (!$post) {
		return $values;
	}

	// load the revision annotation if requested
	if ($revision instanceof ElggAnnotation && $revision->entity_guid == $post->getGUID()) {
		$values['revision'] = $revision;
		$values['description'] = $revision->value;
	}

	// display a notice if there's an autosaved annotation
	// and we're not editing it.
	$auto_save_annotations = $post->getAnnotations([
		'annotation_name' => 'blog_auto_save',
		'limit' => 1,
	]);
	if ($auto_save_annotations) {
		$auto_save = $auto_save_annotations[0];
	} else {
		$auto_save = false;
	}
	/* @var ElggAnnotation|false $auto_save */

	if ($auto_save && $revision && $auto_save->id != $revision->id) {
		$values['draft_warning'] = elgg_echo('blog:messages:warning:draft');
	}

	return $values;
}
