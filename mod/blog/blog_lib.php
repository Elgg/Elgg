<?php
/**
 * Blog helper functions
 *
 * @package Blog
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */


/**
 * Returns HTML for a blog post.
 *
 * @param int $guid of a blog entity.
 * @return string html
 */
function blog_get_page_content_read($owner_guid = NULL, $guid = NULL) {
	$content = elgg_view('page_elements/content_header', array('context' => $context, 'type' => 'blog'));

	if ($guid) {
		$blog = get_entity($guid);

		if (!elgg_instanceof($blog, 'object', 'blog') && $blog->status == 'final') {
			$content .= elgg_echo('blog:error:post_not_found');
		} else {
			elgg_push_breadcrumb($blog->title, $blog->getURL());
			$content .= elgg_view_entity($blog, TRUE);
		}
	} else {
		$options = array(
			'type' => 'object',
			'subtype' => 'blog',
			'full_view' => FALSE,
			'order_by_metadata' => array('name'=>'publish_date', 'direction'=>'DESC', 'as'=>'int')
		);

		if ($owner_guid) {
			$options['owner_guid'] = $owner_guid;
		}

		// show all posts for admin or users looking at their own blogs
		// show only published posts for other users.
		if (!(isadminloggedin() || (isloggedin() && $owner_guid == get_loggedin_userid()))) {
			$options['metadata_name_value_pairs'] = array(
				array('name' => 'status', 'value' => 'published'),
				array('name' => 'publish_date', 'operand' => '<', 'value' => time())
			);
		}

		$content .= elgg_list_entities_from_metadata($options);
	}

	return array('content' => $content);
}

/**
 * Returns HTML to edit a blog post.
 *
 * @param int $guid
 * @param int annotation id optional revision to edit
 * @return string html
 */
function blog_get_page_content_edit($guid, $revision = NULL) {
	$vars = array();
	if ($guid) {
		$blog = get_entity((int)$guid);

		if (elgg_instanceof($blog, 'object', 'blog') && $blog->canEdit()) {
			$vars['entity'] = $blog;

			if ($revision) {
				$revision = get_annotation((int)$revision);
				$vars['revision'] = $revision;

				if (!$revision || !($revision->entity_guid == $guid)) {
					$content = elgg_echo('blog:error:revision_not_found');
				}
			}

			elgg_push_breadcrumb($blog->title, $blog->getURL());
			elgg_push_breadcrumb(elgg_echo('edit'));

			$content = elgg_view('blog/forms/edit', $vars);
			$sidebar = elgg_view('blog/sidebar_revisions', array('entity' => $blog));
			//$sidebar .= elgg_view('blog/sidebar_related');
		} else {
			$content = elgg_echo('blog:error:post_not_found');
		}
	} else {
		elgg_push_breadcrumb(elgg_echo('blog:new'));
		$content = elgg_view('blog/forms/edit', $vars);
		//$sidebar = elgg_view('blog/sidebar_related');
	}

	return array('content' => $content, 'sidebar' => $sidebar);
}

/**
 * Saves a blog
 *
 * @param array $info An array of name=>value pairs to save to the blog entity
 *
 * @return array('success' => BOOL, 'message' => string);
 */
function blog_save_blog($info) {
	// store errors to pass along
	$error = FALSE;

	if ($info['guid']) {
		$entity = get_entity($info['guid']);
		if (elgg_instanceof($entity, 'object', 'blog') && $entity->canEdit()) {
			$blog = $entity;
		} else {
			$error = elgg_echo('blog:error:post_not_found');
		}
	} else {
		$blog = new ElggObject();
		$blog->subtype = 'blog';
	}

	// check required vars
	$required = array('title', 'description');

	// load from POST and do sanity and access checking
	foreach ($info as $name => $value) {
		if (in_array($name, $required) && empty($value)) {
			$error = elgg_echo("blog:error:missing:$name");
		}

		if ($error) {
			break;
		}

		switch ($name) {
			case 'tags':
				if ($value) {
					$info[$name] = string_to_tag_array($value);
				} else {
					unset ($info[$name]);
				}
				break;

			case 'excerpt':
				// restrict to 300 chars
				if ($value) {
					$value = substr(strip_tags($value), 0, 300);
				} else {
					$value = substr(strip_tags($info['description']), 0, 300);
				}
				$info[$name] = $value;
				break;

			case 'container_guid':
				// this can't be empty.
				if (!empty($value)) {
					if (can_write_to_container($user->getGUID(), $value)) {
						$info[$name] = $value;
					} else {
						$error = elgg_echo("blog:error:cannot_write_to_container");
					}
				} else {
					unset($info[$name]);
				}
				break;

			// don't try to set the guid
			case 'guid':
				unset($info['guid']);
				break;

			default:
				$info[$name] = $value;
				break;
		}
	}

	// assign values to the entity, stopping on error.
	if (!$error) {
		foreach ($info as $name => $value) {
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

	if ($error) {
		$return = array(
			'success' => FALSE,
			'message' => $error
		);
	} else {
		$return = array(
			'success' => TRUE,
			'message' => elgg_echo('blog:message:saved')
		);
	}

	return $return;
}

/**
 * Returns an appropriate excerpt for a blog.
 *
 * @param string $text
 * @return string
 */
function blog_make_excerpt($text) {
	return substr(strip_tags($text), 0, 300);
}