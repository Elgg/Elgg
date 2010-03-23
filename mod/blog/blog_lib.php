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
 * Returns an appropriate excerpt for a blog.
 *
 * @param string $text
 * @return string
 */
function blog_make_excerpt($text) {
	return substr(strip_tags($text), 0, 300);
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
	 * Override the value returned for time_created
	 */
	public function __get($name) {
		if ($name == 'time_created') {
			$name = 'time_created';
		}

		return $this->get($name);
	}
}