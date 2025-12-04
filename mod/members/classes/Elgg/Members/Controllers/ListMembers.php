<?php

namespace Elgg\Members\Controllers;

use Elgg\Controllers\GenericContentListing;
use Elgg\Database\QueryBuilder;
use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Values;

/**
 * Show a list of users/members
 *
 * @since 7.0
 */
class ListMembers extends GenericContentListing {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getPageOptions(string $page, array $options): array {
		$options = parent::getPageOptions($page, $options);
		
		$options['filter_id'] = 'members';
		
		return $options;
	}
	
	/**
	 * List online members
	 *
	 * @param array $options listing options
	 *
	 * @return string
	 */
	protected function listOnline(array $options): string {
		$online_options = [
			'wheres' => [
				function(QueryBuilder $qb, $main_alias) {
					return $qb->compare("{$main_alias}.last_action", '>=', Values::normalizeTimestamp('-10 minutes'), ELGG_VALUE_TIMESTAMP);
				}
			],
			'sort_by' => [
				'property' => 'last_action',
				'direction' => 'desc',
			],
		];
		
		return elgg_view_page('', $this->getPageOptions('online', [
			'title' => elgg_echo('collection:user:user:online'),
			'content' => elgg_view('page/list/all', [
				'options' => array_merge($options, $online_options),
				'page' => 'online',
			]),
			'filter_value' => 'online',
			'filter_sorting' => false,
		]));
	}
	
	/**
	 * List popular members
	 *
	 * @param array $options listing options
	 *
	 * @return string
	 */
	protected function listPopular(array $options): string {
		$popular_options = [
			'relationship' => 'friend',
			'inverse_relationship' => false,
			'no_results' => elgg_echo('members:list:popular:none'),
		];
		
		return elgg_view_page('', $this->getPageOptions('popular', [
			'title' => elgg_echo('collection:user:user:popular'),
			'content' => elgg_view('page/list/all', [
				'options' => array_merge($options, $popular_options),
				'page' => 'online',
				'getter' => 'elgg_get_entities_from_relationship_count',
			]),
			'filter_value' => 'popular',
			'filter_sorting' => false,
		]));
	}
	
	/**
	 * Search for members
	 *
	 * @param array $options listing options
	 *
	 * @return string
	 * @throws BadRequestException
	 */
	protected function listSearch(array $options): string {
		$query = $this->request->getParam('member_query');
		if (empty($query)) {
			$e = new BadRequestException(elgg_echo('error:missing_data'));
			$e->setRedirectUrl(elgg_generate_url('collection:user:user:all'));
			throw $e;
		}
		
		$search_options = [
			'query' => $query,
			'distinct' => true,
		];
		
		return elgg_view_page('', $this->getPageOptions('popular', [
			'title' => elgg_echo('members:title:search', [$query]),
			'content' => elgg_view('page/list/all', [
				'options' => array_merge($options, $search_options),
				'page' => 'search',
				'getter' => 'elgg_search',
			]),
			'filter_value' => 'search',
		]));
	}
}
