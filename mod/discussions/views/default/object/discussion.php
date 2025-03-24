<?php
/**
 * Forum topic entity view
 *
 * @uses $vars['entity'] ElggDiscussion to show
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$full_view = (bool) elgg_extract('full_view', $vars, false);
$entity = elgg_extract('entity', $vars, false);
if (!$entity instanceof ElggDiscussion) {
	return;
}

$poster = $entity->getOwnerEntity();
if (!$poster instanceof ElggEntity) {
	$msg = "User {$entity->owner_guid} could not be loaded, and is needed to display entity {$entity->guid}";
	elgg_log($msg, \Psr\Log\LogLevel::WARNING);
	if ($full_view) {
		throw new EntityNotFoundException($msg);
	}
	
	return;
}

if ($entity->status && $entity->status !== 'open') {
	$vars['imprint'] = [
		[
			'icon_name' => 'warning',
			'content' => elgg_echo("status:{$entity->status}"),
			'class' => 'elgg-listing-discussion-status',
		],
	];
}

if ($full_view) {
	$body = elgg_view('output/longtext', ['value' => $entity->description]);

	$responses = null;
	if ($entity->status == 'closed') {
		$body .= elgg_view('discussion/closed');
		
		// need to provide the comments as we can't disable the add form
		$responses = elgg_view_comments($entity, false);
	}
	
	$params = [
		'icon' => true,
		'show_summary' => true,
		'body' => $body,
		'responses' => $responses,
	];

	$params = $params + $vars;
	echo elgg_view('object/elements/full', $params);
} else {
	$comment_text = '';
	if ($entity->countComments() > 0) {
		$comments = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'comment',
			'container_guid' => $entity->guid,
			'limit' => 1,
			'distinct' => false,
			'metadata_name_value_pairs' => ['level' => 1],
		]);
		
		$last_comment = elgg_extract(0, $comments);
		
		if ($last_comment instanceof \ElggComment) {
			$poster = $last_comment->getOwnerEntity();
			
			$comment_text = elgg_echo('discussion:updated', [$poster->getDisplayName(), elgg_view_friendly_time($last_comment->time_created)]);
			$comment_text = elgg_format_element('span', ['class' => 'float-alt'], elgg_view_url($last_comment->getURL(), $comment_text));
		}
	}
	
	// brief view
	$by_line = elgg_view('object/elements/imprint', $vars);
	
	$subtitle = "{$by_line} {$comment_text}";

	$params = [
		'subtitle' => $subtitle,
		'content' => elgg_get_excerpt((string) $entity->description),
		'icon' => true,
	];
	$params = $params + $vars;
	echo elgg_view('object/elements/summary', $params);
}
