<?php

namespace Elgg\Controllers;

use Elgg\Database\QueryBuilder;
use Elgg\Exceptions\Http\EntityNotFoundException;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;

/**
 * Redirect a comment url to the full view of the entity
 * being commented on with the correct offset to show the comment
 *
 * @since 4.0
 */
class CommentEntityRedirector {
	
	/**
	 * Redirect to the comment in context of the containing page
	 *
	 * @param Request $request the Elgg request
	 *
	 * @return ResponseBuilder
	 * @throws EntityNotFoundException
	 */
	public function __invoke(Request $request) {
		
		$comment_guid = (int) $request->getParam('guid');
		$fallback_guid = (int) $request->getParam('container_guid');
		
		$comment = get_entity($comment_guid);
		if (!$comment instanceof \ElggComment) {
			// try fallback if given
			$fallback = get_entity($fallback_guid);
			if (!$fallback instanceof \ElggEntity) {
				throw new EntityNotFoundException(elgg_echo('generic_comment:notfound'));
			}
			
			return elgg_redirect_response($fallback->getURL());
		}
		
		$container = $comment->getContainerEntity();
		if (!$container instanceof \ElggEntity) {
			throw new EntityNotFoundException(elgg_echo('generic_comment:notfound'));
		}
		
		$operator = elgg_comments_are_latest_first($container) ? '>' : '<';
		
		// this won't work with threaded comments, but core doesn't support that yet
		$count = elgg_count_entities([
			'type' => 'object',
			'subtype' => 'comment',
			'container_guid' => $container->guid,
			'wheres' => [
				function(QueryBuilder $qb, $main_alias) use ($comment, $operator) {
					return $qb->compare("{$main_alias}.guid", $operator, $comment->guid, ELGG_VALUE_GUID);
				},
			],
		]);
		$limit = (int) get_input('limit');
		if ($limit < 1) {
			$limit = elgg_comments_per_page($container);
		}
		$offset = floor($count / $limit) * $limit;
		if ($offset < 1) {
			$offset = null;
		}
		
		$url = elgg_http_add_url_query_elements($container->getURL(), [
			'offset' => $offset,
		]);
		
		// make sure there's only one fragment (#)
		$parts = parse_url($url);
		$parts['fragment'] = "elgg-object-{$comment->guid}";
		$url = elgg_http_build_url($parts, false);
		
		return elgg_redirect_response($url);
	}
}
