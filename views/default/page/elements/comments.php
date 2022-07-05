<?php
/**
 * List comments with optional add form
 *
 * @uses $vars['entity']          ElggEntity
 * @uses $vars['show_add_form']   Display add form or not (default true)
 * @uses $vars['show_login_form'] Should the login form show for logged out users (defaults to show_add_form)
 * @uses $vars['id']              Optional id for the div
 * @uses $vars['class']           Optional additional class for the div
 * @uses $vars['limit']           Optional limit value (default is 25)
 */

use Elgg\Database\QueryBuilder;
use Elgg\Database\Clauses\OrderByClause;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$show_add_form = elgg_extract('show_add_form', $vars, true);

$latest_first = elgg_comments_are_latest_first($entity);

$limit = elgg_extract('limit', $vars, get_input('limit'));
if (!isset($limit)) {
	$limit = elgg_comments_per_page($entity);
}

$module_vars = [
	'id' => elgg_extract('id', $vars, 'comments'),
	'class' => elgg_extract_class($vars, 'elgg-comments'),
];

$options = [
	'type' => 'object',
	'subtype' => 'comment',
	'container_guid' => $entity->guid,
	'full_view' => true,
	'limit' => $limit,
	'offset' => (int) get_input('offset'),
	'distinct' => false,
	'url_fragment' => $module_vars['id'],
	'order_by' => [new OrderByClause('e.guid', $latest_first ? 'DESC' : 'ASC')],
	'list_class' => 'comments-list',
	'pagination' => true,
	'preload_owners' => true,
];

$module_title = '';

if (!$entity instanceof \ElggComment) {
	$module_title = elgg_echo('comments');
	
	$options['metadata_name_value_pairs'] = ['level' => 1];
	
	$show_guid = (int) elgg_extract('show_guid', $vars);
	if ($show_guid && $limit) {
		// show the offset that includes the comment
		$count = elgg_count_entities([
			'type' => 'object',
			'subtype' => 'comment',
			'container_guid' => $entity->guid,
			'metadata_name_value_pairs' => ['level' => 1],
			'wheres' => [
				function(QueryBuilder $qb) use ($show_guid, $latest_first) {
					$operator = $latest_first ? '>' : '<';
					
					return $qb->compare('e.guid', $operator, $show_guid, ELGG_VALUE_INTEGER);
				},
			],
		]);
		$options['offset'] = (int) floor($count / $limit) * $limit;
	}
	
	$comments = elgg_get_entities($options);
	
	$count_options = $options;
	unset($count_options['offset']);
	$options['count'] = elgg_count_entities($count_options);
	
	// preload comment threads
	elgg()->thread_preloader->preloadThreads($comments);
} else {
	$comments = elgg()->thread_preloader->getChildren($entity->guid);
	
	// load children of thread
	$options['limit'] = false;
	$options['offset'] = 0;
	$options['pagination'] = false;
	$options['count'] = count($comments);
	
	$module_vars['header'] = false;
}

$comments_list = elgg_view_entity_list($comments, $options);

$content = $comments_list;
$form = '';

$show_login_form = $comments_list ? $show_add_form : false;
$show_login_form = elgg_extract('show_login_form', $vars, $show_login_form);

if ($show_add_form && $entity->canComment()) {
	$form_vars = [
		'id' => "elgg-form-comment-save-{$entity->guid}",
		'prevent_double_submit' => false,
	];
	if ($entity instanceof \ElggComment) {
		$form_vars['class'] = 'hidden';
	}
	
	if (!$entity instanceof \ElggComment && $latest_first && $comments_list && elgg_get_config('comment_box_collapses')) {
		$form_vars['class'] = 'hidden';
		
		$module_vars['menu'] = elgg_view_menu('comments', [
			'items' => [
				[
					'name' => 'add',
					'text' => elgg_echo('generic_comments:add'),
					'href' => '#' . $form_vars['id'],
					'icon' => 'plus',
					'class' => ['elgg-button', 'elgg-button-action', 'elgg-toggle'],
				],
			],
		]);
	}

	$form = elgg_view_form('comment/save', $form_vars, $vars);
} elseif (!elgg_is_logged_in() && $show_login_form) {
	$login_form_contents = elgg_view_form('login', [], ['returntoreferer' => true]);
	
	$login_form = elgg_view('output/longtext', [
		'value' => elgg_echo('generic_comment:login_required'),
	]);
	
	$login_form .= elgg_view_module('dropdown', '', $login_form_contents, ['id' => 'comments-login']);

	$menu = elgg_view('output/url', [
		'href' => elgg_get_login_url([], '#comments-login'),
		'text' => elgg_echo('login'),
		'class' => ['elgg-button', 'elgg-button-action', 'elgg-popup'],
		'data-position' => json_encode([
			'my' => 'right top',
			'at' => 'right bottom',
		]),
	]);
	
	$form = elgg_view_message('notice', $login_form, [
		'menu' => $menu,
		'class' => 'mtl',
	]);
}

if ($latest_first || $entity instanceof \ElggComment) {
	$content = $form . $content;
} else {
	$content .= $form;
}

if (empty($content)) {
	return;
}

echo elgg_view_module('comments', $module_title, $content, $module_vars);
