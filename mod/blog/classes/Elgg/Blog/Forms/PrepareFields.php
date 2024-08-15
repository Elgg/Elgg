<?php

namespace Elgg\Blog\Forms;

/**
 * Prepare the fields for the blog/save form
 *
 * @since 5.0
 */
class PrepareFields {
	
	/**
	 * Prepare fields
	 *
	 * @param \Elgg\Event $event 'form:prepare:fields', 'blog/save'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event): array {
		$vars = $event->getValue();
		
		// input names => defaults
		$values = [
			'container_guid' => elgg_get_page_owner_guid(),
		];
		
		$fields = elgg()->fields->get('object', 'blog');
		foreach ($fields as $field) {
			$default_value = null;
			$name = (string) elgg_extract('name', $field);
			
			if ($name === 'status') {
				$default_value = 'published';
			} elseif ($name === 'comments_on') {
				$default_value = 'On';
			}
			
			$values[$name] = $default_value;
		}
		
		$blog = elgg_extract('entity', $vars);
		if ($blog instanceof \ElggBlog) {
			// load current blog values
			foreach (array_keys($values) as $field) {
				if (isset($blog->{$field})) {
					$values[$field] = $blog->{$field};
				}
			}
			
			if ($blog->status == 'draft') {
				$values['access_id'] = $blog->future_access;
			}
			
			// load the revision annotation if requested
			$revision = elgg_extract('revision', $vars);
			if ($revision instanceof \ElggAnnotation && $revision->entity_guid == $blog->guid) {
				$values['revision'] = $revision;
				$values['description'] = $revision->value;
			}
		}
		
		return array_merge($values, $vars);
	}
}
