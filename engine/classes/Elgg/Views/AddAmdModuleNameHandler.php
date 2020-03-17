<?php

namespace Elgg\Views;

/**
 * Adds module names
 *
 * @since 4.0
 */
class AddAmdModuleNameHandler {
	
	/**
	 * Inserts module names into anonymous modules by handling the 'simplecache:generate' and 'cache:generate' hook.
	 *
	 * @param \Elgg\Hook $hook 'cache:generate' | 'simplecache:generate', 'js'
	 *
	 * @return string|null View content minified (if css/js type)
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$filter = new \Elgg\Amd\ViewFilter();
		return $filter->filter($hook->getParam('view'), $hook->getValue());
	}
}
