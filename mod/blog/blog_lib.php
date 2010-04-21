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
	global $CONFIG;

	if ($guid) {
		$blog = get_entity($guid);

		if (!elgg_instanceof($blog, 'object', 'blog') || ($blog->status != 'published' && !$blog->canEdit())) {
			$content = elgg_echo('blog:error:post_not_found');
		} else {
			elgg_push_breadcrumb($blog->title, $blog->getURL());
			$content = elgg_view_entity($blog, TRUE);
			$content .= elgg_view_comments($blog);
		}
	} else {
		$content = elgg_view('page_elements/content_header', array(
			'context' => $owner_guid ? 'mine' : 'everyone',
			'type' => 'blog',
			'all_link' => "{$CONFIG->site->url}pg/blog"
		));

		$options = array(
			'type' => 'object',
			'subtype' => 'blog',
			'full_view' => FALSE,
			//'order_by_metadata' => array('name'=>'publish_date', 'direction'=>'DESC', 'as'=>'int')
		);

		$loggedin_userid = get_loggedin_userid();
		if ($owner_guid) {
			$options['owner_guid'] = $owner_guid;

			if ($owner_guid != $loggedin_userid) {
				// do not show content header when viewing other users' posts
				$content = elgg_view('page_elements/content_header_member', array('type' => 'blog'));
			}
		}

		// show all posts for admin or users looking at their own blogs
		// show only published posts for other users.
		if (!(isadminloggedin() || (isloggedin() && $owner_guid == $loggedin_userid))) {
			$options['metadata_name_value_pairs'] = array(
				array('name' => 'status', 'value' => 'published'),
				array('name' => 'publish_date', 'operand' => '<', 'value' => time())
			);
		}

		$list = elgg_list_entities_from_metadata($options);
		if (!$list) {
			$content .= elgg_echo('blog:none');
		} else {
			$content .= $list;
		}
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
 * Show blogs with publish dates between $lower and $upper
 *
 * @param unknown_type $owner_guid
 * @param unknown_type $lower
 * @param unknown_type $upper
 */
function blog_get_page_content_archive($owner_guid, $lower=0, $upper=0) {
	global $CONFIG;

	$now = time();

	elgg_push_breadcrumb(elgg_echo('blog:archives'));
	$content = elgg_view('page_elements/content_header_member', array('type' => 'blog'));

	if ($lower) {
		$lower = (int)$lower;
	}

	if ($upper) {
		$upper = (int)$upper;
	}

	$options = array(
		'type' => 'object',
		'subtype' => 'blog',
		'full_view' => FALSE,
		'order_by_metadata' => array('name'=>'publish_date', 'direction'=>'DESC', 'as'=>'int'),
	);

	if ($owner_guid) {
		$options['owner_guid'] = $owner_guid;
	}

	// admin / owners can see any posts
	// everyone else can only see published posts
	if (!(isadminloggedin() || (isloggedin() && $owner_guid == get_loggedin_userid()))) {
		if ($upper > $now) {
			$upper = $now;
		}

		$options['metadata_name_value_pairs'] = array(
			array('name' => 'status', 'value' => 'published')
		);
	}

	if ($lower) {
		$options['metadata_name_value_pairs'][] = array(
			'name' => 'publish_date',
			'operand' => '>',
			'value' => $lower
		);
	}

	if ($upper) {
		$options['metadata_name_value_pairs'][] = array(
			'name' => 'publish_date',
			'operand' => '<',
			'value' => $upper
		);
	}

	$list = elgg_list_entities_from_metadata($options);
	if (!$list) {
		$content .= elgg_echo('blog:none');
	} else {
		$content .= $list;
	}

	return array(
		'content' => $content
	);
}

/**
 * Returns a view of the user's friend's posts.
 *
 * @param int $user_guid
 * @return string
 */
function blog_get_page_content_friends($user_guid) {
	global $CONFIG;

	elgg_push_breadcrumb(elgg_echo('friends'));

	$content = elgg_view('page_elements/content_header', array(
		'context' => 'friends',
		'type' => 'blog',
		'all_link' => "{$CONFIG->site->url}pg/blog"
	));

	if (!$friends = get_user_friends($user_guid, ELGG_ENTITIES_ANY_VALUE, 0)) {
		$content .= elgg_echo('friends:none:you');
	} else {
		$options = array(
			'type' => 'object',
			'subtype' => 'blog',
			'full_view' => FALSE,
			'order_by_metadata' => array('name'=>'publish_date', 'direction'=>'DESC', 'as'=>'int'),
		);

		foreach ($friends as $friend) {
			$options['container_guids'][] = $friend->getGUID();
		}

		// admin / owners can see any posts
		// everyone else can only see published posts
		if (!(isadminloggedin() || (isloggedin() && $owner_guid == get_loggedin_userid()))) {
			if ($upper > $now) {
				$upper = $now;
			}

			$options['metadata_name_value_pairs'][] = array(
				array('name' => 'status', 'value' => 'published')
			);
		}

		$list = elgg_list_entities_from_metadata($options);
		if (!$list) {
			$content .= elgg_echo('blog:none');
		} else {
			$content .= $list;
		}
	}

	return array('content' => $content);
}

/**
 * Returns an appropriate excerpt for a blog.
 * Will return up to 250 chars stopping at the nearest space.
 * If no spaces are found (like in Japanese) will crop off at the
 * 250 char mark.
 *
 * @param string $text
 * @param int $words
 * @return string
 */
function blog_make_excerpt($text, $chars = 250) {
	$text = trim(strip_tags($text));

	// handle cases
	$excerpt = elgg_substr($text, 0, $chars);
	$space = elgg_strrpos($excerpt, ' ', 0);

	// don't crop if can't find a space.
	if ($space === FALSE) {
		$space = $chars;
	}
	$excerpt = trim(elgg_substr($excerpt, 0, $space));

	return $excerpt ;
}

/**
 * Returns a list of years and months for all blogs optionally for a user.
 * Very similar to get_entity_dates() except uses a metadata field.
 *
 * @param mixed $user_guid
 */
function blog_get_blog_months($user_guid = NULL, $container_guid = NULL) {
	global $CONFIG;

	$subtype = get_subtype_id('blog');

	$q = "SELECT DISTINCT EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME(mdv.string)) AS yearmonth
		FROM {$CONFIG->dbprefix}entities e, {$CONFIG->dbprefix}metadata, {$CONFIG->dbprefix}metastrings mdn, {$CONFIG->dbprefix}metastrings mdv
		WHERE e.guid = {$CONFIG->dbprefix}metadata.entity_guid
		AND {$CONFIG->dbprefix}metadata.name_id = mdn.id
		AND {$CONFIG->dbprefix}metadata.value_id = mdv.id
		AND mdn.string = 'publish_date'";

	if ($user_guid) {
		$user_guid = (int)$user_guid;
		$q .= " AND e.owner_guid = $user_guid";
	}

	if ($container_guid) {
		$container_guid = (int)$container_guid;
		$q .= " AND e.container_guid = $container_guid";
	}

	$q .= ' AND ' . get_access_sql_suffix('e');

	return get_data($q);
}

/**
 * Extended class to override the time_created
 */
class ElggBlog extends ElggObject {
	protected function initialise_attributes() {
		parent::initialise_attributes();

		// override the default file subtype.
		$this->attributes['subtype'] = 'blog';
	}

	/**
	 * Rewrite the time created to be publish time.
	 * This is a bit dirty, but required for proper sorting.
	 */
	public function save() {
		if (parent::save()) {
			global $CONFIG;
			$published = $this->publish_date;
			$sql = "UPDATE {$CONFIG->dbprefix}entities SET time_created = '$published', time_updated = '$published'";
			return update_data($sql);
		}

		return FALSE;
	}
}