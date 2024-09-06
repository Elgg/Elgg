<?php

namespace Elgg\Blog;

/**
 * Register fields for blogs
 *
 * @since 6.1
 */
class FieldsHandler {
	
	/**
	 * Register the fields for blogs
	 *
	 * @param \Elgg\Event $event 'fields', 'object:blog'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event): array {
		$result = (array) $event->getValue();
		
		$result[] = [
			'#type' => 'text',
			'#label' => elgg_echo('title'),
			'name' => 'title',
			'required' => true,
			'id' => 'blog_title',
		];
		
		$result[] = [
			'#type' => 'text',
			'#label' => elgg_echo('blog:excerpt'),
			'name' => 'excerpt',
			'id' => 'blog_excerpt',
		];
		
		$result[] = [
			'#type' => 'longtext',
			'#label' => elgg_echo('blog:body'),
			'name' => 'description',
			'required' => true,
			'id' => 'blog_description',
		];
		
		$result[] = [
			'#type' => 'tags',
			'#label' => elgg_echo('tags'),
			'name' => 'tags',
			'id' => 'blog_tags',
		];
		$result[] = [
			'#type' => 'checkbox',
			'#label' => elgg_echo('comments'),
			'name' => 'comments_on',
			'id' => 'blog_comments_on',
			'default' => 'Off',
			'value' => 'On',
			'switch' => true,
		];
		
		$result[] = [
			'#type' => 'access',
			'#label' => elgg_echo('access'),
			'name' => 'access_id',
			'id' => 'blog_access_id',
			'entity_type' => 'object',
			'entity_subtype' => 'blog',
		];
		
		$result[] = [
			'#type' => 'select',
			'#label' => elgg_echo('status'),
			'name' => 'status',
			'id' => 'blog_status',
			'options_values' => [
				'draft' => elgg_echo('status:draft'),
				'published' => elgg_echo('status:published'),
			],
		];
		
		return $result;
	}
}
