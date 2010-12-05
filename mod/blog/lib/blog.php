<?php
/**
 * Blog helper functions
 *
 * @package Blog
 */


/**
 * Returns HTML for a blog post.
 *
 * @param int $guid of a blog entity.
 * @return string html
 */
function blog_get_page_content_read($guid = NULL) {

	$return = array();

	$blog = get_entity($guid);

	// no header or tabs for viewing an individual blog
	$return['filter'] = '';
	$return['header'] = '';

	if (!elgg_instanceof($blog, 'object', 'blog') || !$blog->canEdit()) {
		$return['content'] = elgg_echo('blog:error:post_not_found');
		return $return;
	}

	elgg_push_breadcrumb($blog->title, $blog->getURL());
	$return['content'] = elgg_view_entity($blog, TRUE);
	//check to see if comment are on
	if ($blog->comments_on != 'Off') {
		$return['content'] .= elgg_view_comments($blog);
	}

	return $return;
}

/**
 * Returns HTML for listing a user's or all blogs.
 *
 * @param int $owner_guid The GUID of the page owner or NULL for all blogs
 * @return string html
 */
function blog_get_page_content_list($owner_guid = NULL) {

	$return = array();

	$return['filter_context'] = $owner_guid ? 'mine' : 'everyone';

	$options = array(
		'type' => 'object',
		'subtype' => 'blog',
		'full_view' => FALSE,
		//'order_by_metadata' => array('name'=>'publish_date', 'direction'=>'DESC', 'as'=>'int')
	);

	$loggedin_userid = get_loggedin_userid();
	if ($owner_guid) {
		$options['owner_guid'] = $owner_guid;

		if ($owner_guid == $loggedin_userid) {
			$return['filter_context'] = 'mine';
		} else{
			// do not show button or select a tab when viewing someone else's posts
			$return['filter_context'] = 'none';
			$return['buttons'] = '';
		}
	} else {
		$return['filter_context'] = 'everyone';
	}

	// show all posts for admin or users looking at their own blogs
	// show only published posts for other users.
	if (!(isadminloggedin() || (isloggedin() && $owner_guid == $loggedin_userid))) {
		$options['metadata_name_value_pairs'] = array(
			array('name' => 'status', 'value' => 'published'),
			//array('name' => 'publish_date', 'operand' => '<', 'value' => time())
		);
	}

	$list = elgg_list_entities_from_metadata($options);
	if (!$list) {
		$return['content'] = elgg_echo('blog:none');
	} else {
		$return['content'] = $list;
	}

	return $return;
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
			$sidebar = elgg_view('blog/sidebar_revisions', $vars);
			//$sidebar .= elgg_view('blog/sidebar_related');
		} else {
			$content = elgg_echo('blog:error:cannot_edit_post');
		}
	} else {
		elgg_push_breadcrumb(elgg_echo('blog:new'));
		$content = elgg_view('blog/forms/edit', $vars);
		//$sidebar = elgg_view('blog/sidebar_related');
	}

	return array(
		'content' => $content,
		'sidebar' => $sidebar,
		'header' => '',
		'filter' => '',
	);
}

/**
 * Show blogs with publish dates between $lower and $upper
 *
 * @param int $owner_guid The GUID of the owner of this page
 * @param int $lower      Unix timestamp
 * @param int $upper      Unix timestamp
 */
function blog_get_page_content_archive($owner_guid, $lower=0, $upper=0) {

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
		'content' => $content,
		'filter' => '',
		'header' => '',
	);
}

/**
 * Returns a list of the user's friend's posts.
 *
 * @param int $user_guid
 * @return string
 */
function blog_get_page_content_friends($user_guid) {

	elgg_push_breadcrumb(elgg_echo('friends'));

	$return = array();

	$return['filter_context'] = 'friends';

	if (!$friends = get_user_friends($user_guid, ELGG_ENTITIES_ANY_VALUE, 0)) {
		$return['content'] .= elgg_echo('friends:none:you');
		return $return;
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
			$return['content'] = elgg_echo('blog:none');
		} else {
			$return['content'] = $list;
		}
	}

	return $return;
}

/**
 * Returns a list of years and months for all blogs optionally for a user.
 * Very similar to get_entity_dates() except uses a metadata field.
 *
 * @param mixed $user_guid
 */
function blog_get_blog_months($user_guid = NULL, $container_guid = NULL) {
	global $CONFIG;

	$subtype = get_subtype_id('object', 'blog');

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
