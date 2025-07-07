<?php

namespace Elgg\TheWire\Controllers;

use Elgg\Controllers\GenericContentListing;
use Elgg\Exceptions\Http\BadRequestException;

/**
 * TheWire content listings
 *
 * @since 7.0
 */
class ContentListing extends GenericContentListing {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getPageOptions(string $page, array $options): array {
		$options = parent::getPageOptions($page, $options);
		
		$user = elgg_get_logged_in_user_entity();
		$add_form = false;
		if ($page === 'all') {
			$add_form = ($user instanceof \ElggUser);
		} elseif (in_array($page, ['friends', 'owner'])) {
			$add_form = $user?->guid === $this->page_owner?->guid;
		}
		
		if ($add_form) {
			$form = elgg_view_form('thewire/add', [
				'class' => 'thewire-form',
			]);
			
			$options['content'] = $form . $options['content'];
		}
		
		return $options;
	}
	
	/**
	 * Show a Wire thread
	 *
	 * @param array $options listing options
	 *
	 * @return string
	 * @throws BadRequestException
	 */
	protected function listThread(array $options): string {
		$original_post = $this->request->getEntityParam();
		if (!$original_post instanceof \ElggWire) {
			throw new BadRequestException();
		}
		
		elgg_push_entity_breadcrumbs($original_post);
		
		$thread_options = [
			'metadata_name_value_pairs' => [
				'name' => 'wire_thread',
				'value' => $original_post->guid,
				'type' => ELGG_VALUE_INTEGER,
			],
		];
		
		return elgg_view_page('', $this->getPageOptions('thread', [
			'title' => elgg_echo('thewire:thread'),
			'content' => elgg_view('page/list/all', [
				'page' => 'thread',
				'entity' => $original_post,
				'options' => array_merge($options, $thread_options),
			]),
			'filter_id' => 'thewire/thread',
		]));
	}
	
	/**
	 * Show Wire posts with a given tag
	 *
	 * @param array $options listing options
	 *
	 * @return string
	 * @throws BadRequestException
	 */
	protected function listTag(array $options): string {
		$tag = $this->request?->getParam('tag');
		if (elgg_is_empty($tag)) {
			throw new BadRequestException();
		}
		
		elgg_push_collection_breadcrumbs('object', 'thewire');
		
		// remove # from tag
		$tag = trim($tag, '# ');
		
		$tag_options = [
			'metadata_name_value_pairs' => [
				'name' => 'tags',
				'value' => $tag,
				'case_sensitive' => false,
			],
		];
		
		return elgg_view_page('', $this->getPageOptions('tag', [
			'title' => elgg_echo('thewire:tags', [$tag]),
			'content' => elgg_view('page/list/all', [
				'page' => 'tag',
				'options' => array_merge($options, $tag_options),
			]),
			'filter_value' => 'tag',
		]));
	}
	
	/**
	 * Show Wire posts with a mentioned (@{$username}) username
	 *
	 * @param array $options listing options
	 *
	 * @return string
	 */
	protected function listMentions(array $options): string {
		elgg_push_collection_breadcrumbs('object', 'thewire', $this->page_owner);
		
		$mention_options = [
			'metadata_name_value_pairs' => [
				'name' => 'description',
				'value' => "%@{$this->page_owner->username}%",
				'operand' => 'LIKE',
			],
		];
		
		return elgg_view_page('', $this->getPageOptions('mentions', [
			'title' => elgg_echo('collection:object:thewire:mentions', [$this->page_owner->username]),
			'content' => elgg_view('page/list/all', [
				'page' => 'mentions',
				'entity' => $this->page_owner,
				'options' => array_merge($options, $mention_options),
			]),
			'filter_value' => 'mentions',
		]));
	}
}
