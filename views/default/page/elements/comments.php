<?php
/**
 * List comments with optional add form
 *
 * @uses $vars['entity']        ElggEntity
 * @uses $vars['show_add_form'] Display add form or not
 * @uses $vars['id']            Optional id for the div
 * @uses $vars['class']         Optional additional class for the div
 * @uses $vars['limit']         Optional limit value (default is 25)
 */

use Elgg\Database\QueryBuilder;
use Elgg\Database\Clauses\OrderByClause;

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$show_add_form = elgg_extract('show_add_form', $vars, true);

$latest_first = elgg_comments_are_latest_first($entity);

$limit = elgg_extract('limit', $vars, get_input('limit', 0));
if (!$limit) {
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
	'distinct' => false,
	'url_fragment' => $module_vars['id'],
	'order_by' => [new OrderByClause('e.guid', $latest_first ? 'DESC' : 'ASC')],
	'list_class' => 'comments-list',
];

$show_guid = (int) elgg_extract('show_guid', $vars);
if ($show_guid && $limit) {
	// show the offset that includes the comment
	// this won't work with threaded comments, but core doesn't support that yet
	$operator = $latest_first ? '>' : '<';
	$condition = function(QueryBuilder $qb) use ($show_guid, $operator) {
		return $qb->compare('e.guid', $operator, $show_guid, ELGG_VALUE_INTEGER);
	};
	$count = elgg_get_entities([
		'type' => 'object',
		'subtype' => 'comment',
		'container_guid' => $entity->guid,
		'count' => true,
		'wheres' => [$condition],
	]);
	$options['offset'] = (int) floor($count / $limit) * $limit;
}

$comments_list = elgg_list_entities($options);

$content = '';
if ($show_add_form && $entity->canComment()) {
	$form_vars = [];
	if ($comments_list) {
		$form_vars['class'] = 'hidden';
		
		$module_vars['menu'] = elgg_view_menu('comments', [
			'items' => [
				[
					'name' => 'add',
					'text' => elgg_echo('generic_comments:add'),
					'href' => false,
					'icon' => 'plus',
					'class' => ['elgg-button', 'elgg-button-action'],
					'rel' => 'toggle',
					'data-toggle-selector' => '.elgg-form-comment-save',
				],
			],
		]);
	}
	
	$content .= elgg_view_form('comment/save', $form_vars, $vars);
}

$content .= $comments_list;

if (empty($content)) {
	return;
}

echo elgg_view_module('comments', elgg_echo('comments'), $content, $module_vars);
