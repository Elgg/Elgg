<?php

namespace Elgg\Controllers;

use Elgg\Database\QueryBuilder;
use Elgg\Http\ResponseBuilder;
use Elgg\Request;
use Elgg\Values;

/**
 * Returns the online user count
 *
 * @since 6.0
 */
class OnlineUsersCount {
	
	/**
	 * Returns the online users count
	 *
	 * @param Request $request the Elgg request
	 *
	 * @return ResponseBuilder
	 */
	public function __invoke(Request $request) {
		$online_users_count = max(1, elgg_count_entities([
			'type' => 'user',
			'wheres' => [
				function(QueryBuilder $qb, $main_alias) {
					return $qb->compare("{$main_alias}.last_action", '>=', Values::normalizeTimestamp('-10 minutes'), ELGG_VALUE_TIMESTAMP);
				}
			],
		]));
		
		return elgg_ok_response([
			'number' => $online_users_count,
			'formatted' => Values::shortFormatOutput($online_users_count),
		]);
	}
}
