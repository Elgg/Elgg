<?php 

/**
 * List blogs
 *
 * @param array $vars options,created_after,created_before and status
 *
 * @return string
 */

function blog_listing_all($vars) {
	$defaults = [
		'type' => 'object',
		'subtype' => 'blog',
		'full_view' => false,
		'no_results' => elgg_echo('blog:none'),
		'distinct' => false,
	];

	$options = (array) $vars['options'];
	$options = array_merge($defaults, $options);

	if ($after = $vars['created_after']) {
		$options['created_after'] = $after;
	}

	if ($before = $vars['created_before']) {
		$options['created_before'] = $before;
	}

	if ($status = $vars['status']) {
		$options['metadata_name_value_pairs']['status'] = $status;
	}

	return elgg_list_entities($options);
}

/**
 * List friends' blogs
 *
 * @param array $vars entity,created_after,created_before and status
 *
 * @return string
 */

function blog_listing_friends($vars) {
	$entity = $vars['entity'];

	$vars['options'] = [
		'relationship' => 'friend',
		'relationship_guid' => (int) $entity->guid,
		'relationship_join_on' => 'owner_guid',
	];

	return blog_listing_all($vars);
}

/**
 * List group blogs
 *
 * @param array $vars entity,created_after,created_before and status
 *
 * @return string
 */

function blog_listing_group($vars) {
	$entity = $vars['entity'];

	$vars['options'] = [
		'container_guids' => (int) $entity->guid,
		'preload_containers' => false,
	];

	return blog_listing_all($vars);
}

/**
 * List user blogs
 *
 * @param array $vars entity,created_after,created_before and status
 *
 * @return string
 */

function blog_listing_owner($vars) {
	$entity = $vars['entity'];

	$vars['options'] = [
		'owner_guids' => (int) $entity->guid,
		'preload_owners' => false,
	];

	return blog_listing_all($vars);
}
 ?>