<?php

namespace Elgg\Views;

/**
 * Adds module names
 *
 * @since 4.0
 */
class AddAmdModuleNameHandler {
	
	/**
	 * Inserts module names into anonymous modules by handling the 'simplecache:generate' and 'cache:generate' event.
	 *
	 * @param \Elgg\Event $event 'cache:generate' | 'simplecache:generate', 'js'
	 *
	 * @return string|null View content minified (if css/js type)
	 */
	public function __invoke(\Elgg\Event $event) {
		$filter = new \Elgg\Amd\ViewFilter();
		return $filter->filter($event->getParam('view'), $event->getValue());
	}
}
