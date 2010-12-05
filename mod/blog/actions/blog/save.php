<?php
/**
 * Save blog entity
 *
 * @package Blog
 */

// start a new sticky form session in case of failure
//elgg_make_sticky_form();

// store errors to pass along
$error = FALSE;
$error_forward_url = REFERER;
$user = get_loggedin_user();

// edit or create a new entity
$guid = get_input('guid');

if ($guid) {
	$entity = get_entity($guid);
	if (elgg_instanceof($entity, 'object', 'blog') && $entity->canEdit()) {
		$blog = $entity;
	} else {
		register_error(elgg_echo('blog:error:post_not_found'));
		forward(get_input('forward', REFERER));
	}
	$success_forward_url = get_input('forward', $blog->getURL());

	// save some data for revisions once we save the new edit
	$revision_text = $blog->description;
	$new_post = $blog->new_post;
} else {
	$blog = new ElggBlog();
	$blog->subtype = 'blog';
	$success_forward_url = get_input('forward');
	$new_post = TRUE;
}

// set defaults and required values.
$values = array(
	'title' => '',
	'description' => '',
	'status' => 'draft',
	'access_id' => ACCESS_DEFAULT,
	'comments_on' => 'On',
	'excerpt' => '',
	'tags' => '',
	'container_guid' => ''
);

// fail if a required entity isn't set
$required = array('title', 'description');

// load from POST and do sanity and access checking
foreach ($values as $name => $default) {
	$value = get_input($name, $default);

	if (in_array($name, $required) && empty($value)) {
		$error = elgg_echo("blog:error:missing:$name");
	}

	if ($error) {
		break;
	}

	switch ($name) {
		case 'tags':
			if ($value) {
				$values[$name] = string_to_tag_array($value);
			} else {
				unset ($values[$name]);
			}
			break;

		case 'excerpt':
			if ($value) {
				$value = elgg_get_excerpt($value);
			} else {
				$value = elgg_get_excerpt($values['description']);
			}
			$values[$name] = $value;
			break;

		case 'container_guid':
			// this can't be empty or saving the base entity fails
			if (!empty($value)) {
				if (can_write_to_container($user->getGUID(), $value)) {
					$values[$name] = $value;
				} else {
					$error = elgg_echo("blog:error:cannot_write_to_container");
				}
			} else {
				unset($values[$name]);
			}
			break;

		// don't try to set the guid
		case 'guid':
			unset($values['guid']);
			break;

		default:
			$values[$name] = $value;
			break;
	}
}

// build publish_date
//$publish_month = get_input('publish_month');
//$publish_day = get_input('publish_day');
//$publish_year = get_input('publish_year');
//$publish_hour = get_input('publish_hour');
//$publish_minute = get_input('publish_minute');
//$datetime = "$publish_year-$publish_month-$publish_day $publish_hour:$publish_minute:00";
//$values['publish_date'] = date('U', strtotime($datetime));

// setting publish_date to now until the problems with locale dates can be resolved.
$values['publish_date'] = time();

// assign values to the entity, stopping on error.
if (!$error) {
	foreach ($values as $name => $value) {
		if (FALSE === ($blog->$name = $value)) {
			$error = elgg_echo('blog:error:cannot_save' . "$name=$value");
			break;
		}
	}
}

// only try to save base entity if no errors
if (!$error) {
	if ($blog->save()) {
		// remove sticky form entries
		elgg_clear_sticky_form();

		// remove autosave draft if exists
		$blog->clearAnnotations('blog_auto_save');

		// no longer a brand new post.
		$blog->clearMetadata('new_post');

		// if this was an edit, create a revision annotation
		if (!$new_post && $revision_text) {
			$blog->annotate('blog_revision', $revision_text);
		}

		system_message(elgg_echo('blog:message:saved'));

		// @todo do we want to alert on updates?
		if ($new_post && $blog->status == 'published') {
			add_to_river('river/object/blog/create', 'create', get_loggedin_userid(), $blog->getGUID());
		}
		forward($success_forward_url);
	} else {
		register_error(elgg_echo('blog:error:cannot_save'));
		forward($error_forward_url);
	}
} else {
	register_error($error);
	forward($error_forward_url);
}