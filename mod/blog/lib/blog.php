<?php
/**
 * Blog helper functions
 *
 * @package Blog
 */


/**
 * Get page components to view a blog post.
 *
 * @param int $guid GUID of a blog entity.
 * @return array
 */
function blog_get_page_content_read($guid = NULL) {

	$return = array();

	$blog = get_entity($guid);

	// no header or tabs for viewing an individual blog
	$return['filter'] = '';
	$return['header'] = '';

	if (!elgg_instanceof($blog, 'object', 'blog')) {
		$return['content'] = elgg_echo('blog:error:post_not_found');
		return $return;
	}

	$return['title'] = htmlspecialchars($blog->title);

	$container = $blog->getContainerEntity();
	$crumbs_title = $container->name;
	if (elgg_instanceof($container, 'group')) {
		elgg_push_breadcrumb($crumbs_title, "pg/blog/group/$container->guid/owner");
	} else {
		elgg_push_breadcrumb($crumbs_title, "pg/blog/owner/$container->username");
	}

	elgg_push_breadcrumb($blog->title);
	$return['content'] = elgg_view_entity($blog, TRUE);
	//check to see if comment are on
	if ($blog->comments_on != 'Off') {
		$return['content'] .= elgg_view_comments($blog);
	}

	return $return;
}

/**
 * Get page components to list a user's or all blogs.
 *
 * @param int $owner_guid The GUID of the page owner or NULL for all blogs
 * @return array
 */
function blog_get_page_content_list($container_guid = NULL) {

	$return = array();

	$return['filter_context'] = $container_guid ? 'mine' : 'all';

	$options = array(
		'type' => 'object',
		'subtype' => 'blog',
		'full_view' => FALSE,
		//'order_by_metadata' => array('name'=>'publish_date', 'direction'=>'DESC', 'as'=>'int')
	);

	$loggedin_userid = elgg_get_logged_in_user_guid();
	if ($container_guid) {
		$options['container_guid'] = $container_guid;
		$container = get_entity($container_guid);
		if (!$container) {

		}
		$return['title'] = elgg_echo('blog:title:user_blogs', array($container->name));

		$crumbs_title = $container->name;
		elgg_push_breadcrumb($crumbs_title);

		if ($container_guid == $loggedin_userid) {
			$return['filter_context'] = 'mine';
		} else {
			// do not show button or select a tab when viewing someone else's posts
			$return['filter_context'] = 'none';
			$return['buttons'] = '';
		}

		if (elgg_instanceof($container, 'group')) {
			$return['filter'] = '';
			if ($container->isMember(elgg_get_logged_in_user_entity())) {
				$url = "pg/blog/add/$container->guid";
				$params = array(
					'href' => $url,
					'text' => elgg_echo("blog:add"),
					'class' => 'elgg-button-action',
				);
				$buttons = elgg_view('output/url', $params);
				$return['buttons'] = $buttons;
			}
		}
	} else {
		$return['filter_context'] = 'all';
		$return['title'] = elgg_echo('blog:title:all_blogs');
	}

	// show all posts for admin or users looking at their own blogs
	// show only published posts for other users.
	if (!(elgg_is_admin_logged_in() || (elgg_is_logged_in() && $container_guid == $loggedin_userid))) {
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
 * Get page components to list of the user's friends' posts.
 *
 * @param int $user_guid
 * @return array
 */
function blog_get_page_content_friends($user_guid) {

	$user = get_user($user_guid);

	$return = array();

	$return['filter_context'] = 'friends';
	$return['title'] = elgg_echo('blog:title:friends');

	$crumbs_title = $user->name;
	elgg_push_breadcrumb($crumbs_title, "pg/blog/owner/{$user->username}");
	elgg_push_breadcrumb(elgg_echo('friends'));

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
		if (!(elgg_is_admin_logged_in() || (elgg_is_logged_in() && $owner_guid == elgg_get_logged_in_user_guid()))) {
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
 * Get page components to show blogs with publish dates between $lower and $upper
 *
 * @param int $owner_guid The GUID of the owner of this page
 * @param int $lower      Unix timestamp
 * @param int $upper      Unix timestamp
 * @return array
 */
function blog_get_page_content_archive($owner_guid, $lower = 0, $upper = 0) {

	$now = time();

	$user = get_user($owner_guid);
	elgg_set_page_owner_guid($owner_guid);

	$crumbs_title = $user->name;
	elgg_push_breadcrumb($crumbs_title, "pg/blog/owner/{$user->username}");
	elgg_push_breadcrumb(elgg_echo('blog:archives'));

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
	if (!(elgg_is_admin_logged_in() || (elgg_is_logged_in() && $owner_guid == elgg_get_logged_in_user_guid()))) {
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

	$title = elgg_echo('date:month:' . date('n', $lower), array(date('Y', $lower)));

	return array(
		'content' => $content,
		'title' => $title,
		'buttons' => '',
		'filter' => '',
	);
}

/**
 * Get page components to edit/create a blog post.
 *
 * @param string  $page     'edit' or 'new'
 * @param int     $guid     GUID of blog post or container
 * @param int     $revision Annotation id for revision to edit (optional)
 * @return array
 */
function blog_get_page_content_edit($page, $guid = 0, $revision = NULL) {

	$return = array(
		'buttons' => '',
		'filter' => '',
	);

	$vars = array();
	$vars['id'] = 'blog-post-edit';
	$vars['name'] = 'blog_post';

	if ($page == 'edit') {
		$blog = get_entity((int)$guid);

		$title = elgg_echo('blog:edit');

		if (elgg_instanceof($blog, 'object', 'blog') && $blog->canEdit()) {
			$vars['entity'] = $blog;

			$title .= ": \"$blog->title\"";

			if ($revision) {
				$revision = elgg_get_annotation_from_id((int)$revision);
				$vars['revision'] = $revision;
				$title .= ' ' . elgg_echo('blog:edit_revision_notice');

				if (!$revision || !($revision->entity_guid == $guid)) {
					$content = elgg_echo('blog:error:revision_not_found');
					$return['content'] = $content;
					$return['title'] = $title;
					return $return;
				}
			}

			$body_vars = blog_prepare_form_vars($blog, $revision);

			elgg_push_breadcrumb($blog->title, $blog->getURL());
			elgg_push_breadcrumb(elgg_echo('edit'));

			$content = elgg_view_form('blog/save', $vars, $body_vars);
			$content .= elgg_view('js/blog/save_draft');
			$sidebar = elgg_view('blog/sidebar/revisions', $vars);
		} else {
			$content = elgg_echo('blog:error:cannot_edit_post');
		}
	} else {
		if (!$guid) {
			$container = elgg_get_logged_in_user_entity();
		} else {
			$container = get_entity($guid);
		}

		elgg_push_breadcrumb(elgg_echo('blog:add'));
		$body_vars = blog_prepare_form_vars($blog);

		$title = elgg_echo('blog:add');
		$content = elgg_view_form('blog/save', $vars, $body_vars);
		$content .= elgg_view('js/blog/save_draft');
	}

	$return['title'] = $title;
	$return['content'] = $content;
	$return['sidebar'] = $sidebar;
	return $return;	
}

/**
 * Pull together blog variables for the save form
 *
 * @param ElggBlog       $post
 * @param ElggAnnotation $revision
 * @return array
 */
function blog_prepare_form_vars($post = NULL, $revision = NULL) {

	// input names => defaults
	$values = array(
		'title' => NULL,
		'description' => NULL,
		'status' => 'published',
		'publish_date' => NULL,
		'access_id' => ACCESS_DEFAULT,
		'comments_on' => 'On',
		'excerpt' => NULL,
		'tags' => NULL,
		'container_guid' => NULL,
		'guid' => NULL,
		'draft_warning' => '',
	);

	if (elgg_is_sticky_form('blog')) {
		foreach (array_keys($values) as $field) {
			$values[$field] = elgg_get_sticky_value('blog', $field);
		}
	}

	elgg_clear_sticky_form('blog');

	if (!$post) {
		return $values;
	}

	foreach (array_keys($values) as $field) {
		$values[$field] = $post->$field;
	}

	$values['entity'] = $post;

	// load the revision annotation if requested
	if ($revision instanceof ElggAnnotation && $revision->entity_guid == $post->getGUID()) {
		$values['revision'] = $revision;
		$values['description'] = $revision->value;
	}

	// display a notice if there's an autosaved annotation
	// and we're not editing it.
	if ($auto_save_annotations = $post->getAnnotations('blog_auto_save', 1)) {
		$auto_save = $auto_save_annotations[0];
	} else {
		$auto_save == FALSE;
	}

	if ($auto_save && $auto_save->id != $revision->id) {
		$values['draft_warning'] = elgg_echo('blog:messages:warning:draft');
	}

	return $values;
}

/**
 * Returns a list of years and months for all blogs optionally for a user.
 * Very similar to get_entity_dates() except uses a metadata field.
 *
 * @param int $user_guid
 * @param int $container_guid
 * @return array
 */
function blog_get_blog_months($user_guid = NULL, $container_guid = NULL) {

	$db_prefix = elgg_get_config('dbprefix');

	$subtype = get_subtype_id('object', 'blog');

	$q = "SELECT DISTINCT EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME(mdv.string)) AS yearmonth
		FROM {$db_prefix}entities e, {$db_prefix}metadata, {$db_prefix}metastrings mdn, {$db_prefix}metastrings mdv
		WHERE e.guid = {$db_prefix}metadata.entity_guid
		AND {$db_prefix}metadata.name_id = mdn.id
		AND {$db_prefix}metadata.value_id = mdv.id
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
 * Forward to the new style of URLs
 *
 * @param string $page
 */
function blog_url_forwarder($page) {
	global $CONFIG;

	// group usernames
	if (substr_count($page[0], 'group:')) {
		preg_match('/group\:([0-9]+)/i', $page[0], $matches);
		$guid = $matches[1];
		$entity = get_entity($guid);
		if ($entity) {
			$url = "{$CONFIG->wwwroot}pg/blog/group/$guid/owner";
			register_error(elgg_echo("changebookmark"));
			forward($url);
		}
	}

	// user usernames
	$user = get_user_by_username($page[0]);
	if (!$user) {
		return;
	}

	if (!isset($page[1])) {
		$page[1] = 'owner';
	}

	switch ($page[1]) {
		case "read":
			$url = "{$CONFIG->wwwroot}pg/blog/view/{$page[2]}/{$page[3]}";
			break;
		case "archive":
			$url = "{$CONFIG->wwwroot}pg/blog/archive/{$page[0]}/{$page[2]}/{$page[3]}";
			break;
		case "friends":
			$url = "{$CONFIG->wwwroot}pg/blog/friends/{$page[0]}";
			break;
		case "new":
			$url = "{$CONFIG->wwwroot}pg/blog/add/$user->guid";
			break;
		case "owner":
			$url = "{$CONFIG->wwwroot}pg/blog/owner/{$page[0]}";
			break;
	}

	register_error(elgg_echo("changebookmark"));
	forward($url);
}
