<?php
/**
 * Save blog entity
 *
 * @package Blog
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */

elgg_make_sticky_form();

// edit or create a new entity
$guid = get_input('guid');
$user = get_loggedin_user();
$ajax = get_input('ajax');

// store errors to pass along
$error = FALSE;
$error_forward_url = $_SERVER['HTTP_REFERER'];

if ($guid) {
	$entity = get_entity($guid);
	if (elgg_instanceof($entity, 'object', 'blog') && $entity->canEdit()) {
		$blog = $entity;
	} else {
		register_error(elgg_echo('blog:error:post_not_found'));
		forward(get_input('forward', $_SERVER['HTTP_REFERER']));
	}
	$success_forward_url = get_input('forward', $blog->getURL());
} else {
	$blog = new ElggObject();
	$blog->subtype = 'blog';
	$success_forward_url = get_input('forward');
}

// set defaults and required values.
$values = array(
	'title' => '',
	'description' => '',
	'status' => 'draft',
	//'publish_date' => '',
	'access_id' => ACCESS_DEFAULT,
	'comments_on' => 'On',
	'excerpt' => '',
	'tags' => '',
	'container_guid' => ''
);

$required = array('title', 'description');

foreach ($values as $name => $default) {
	$values[$name] = get_input($name, $default);
}


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
			// restrict to 300 chars
			if ($value) {
				$value = substr(strip_tags($value), 0, 300);
			} else {
				$value = substr(strip_tags($values['description']), 0, 300);
			}
			$values[$name] = $value;
			break;

		case 'container_guid':
			// this can't be empty.
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

// assign values to the entity, stopping on error.
if (!$error) {
	foreach ($values as $name => $value) {
		if (!$blog->$name = $value) {
			$error = elgg_echo('blog:error:cannot_save');
			break;
		}
	}
}

// only try to save base entity if no errors
if (!$error && !$blog->save()) {
	$error = elgg_echo('blog:error:cannot_save');
}

// forward with success or failure
if ($ajax) {
	if ($error) {
		$json = array('success' => FALSE, 'message' => $error);
		echo json_encode($json);
	} else {
		$msg = elgg_echo('blog:message:saved');
		$json = array('success' => TRUE, 'message' => $msg, 'guid' => $blog->getGUID());
		echo json_encode($json);
	}
} else {
	if ($error) {
		register_error($error);
		forward($error_forward_url);
	} else {
		system_message(elgg_echo('blog:message:saved'));
		forward($success_forward_url);
	}
}