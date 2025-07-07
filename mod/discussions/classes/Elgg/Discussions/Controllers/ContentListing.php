<?php

namespace Elgg\Discussions\Controllers;

use Elgg\Controllers\GenericContentListing;
use Elgg\Exceptions\Http\BadRequestException;

/**
 * Discussion content listing controller
 *
 * @since 7.0
 */
class ContentListing extends GenericContentListing {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getListingOptions(string $page, array $options): array {
		$options = parent::getListingOptions($page, $options);
		
		$defaults = [
			'sort_by' => [
				'property' => 'last_action',
				'direction' => 'desc',
			],
		];
		
		return array_merge($defaults, $options);
	}
	
	/**
	 * My groups content item listing
	 *
	 * for the route:
	 * - collection:object:discussion:my_groups
	 *
	 * @param array $options listing options
	 *
	 * @return string
	 * @throws BadRequestException
	 */
	protected function listMyGroups(array $options): string {
		if (!$this->page_owner instanceof \ElggUser) {
			throw new BadRequestException();
		}
		
		elgg_push_collection_breadcrumbs($options['type'], $options['subtype'], $this->page_owner);
		
		$my_groups = $this->page_owner->getGroups([
			'limit' => false,
			'callback' => function($row) {
				return (int) $row->guid;
			},
		]);
		if (empty($my_groups)) {
			$content = elgg_view_no_results(elgg_echo('discussion:error:no_groups'));
		} else {
			$my_group_options = [
				'container_guids' => $my_groups,
			];
			
			$content = elgg_view('page/list/all', [
				'entity' => $this->page_owner,
				'options' => array_merge($options, $my_group_options),
				'page' => 'my_groups',
			]);
		}
		
		return elgg_view_page('', $this->getPageOptions('my_groups', [
			'title' => elgg_echo('collection:object:discussion:my_groups'),
			'content' => $content,
			'filter_value' => $this->page_owner->guid === elgg_get_logged_in_user_guid() ? 'my_groups' : 'none',
		]));
	}
}
