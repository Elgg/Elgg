<?php

namespace Elgg\Views;

/**
 * Preprocesses css
 *
 * @since 4.0
 */
class PreProcessCssHandler {
	
	/**
	 * Preprocesses CSS views sent by /cache URLs
	 *
	 * @param \Elgg\Hook $hook 'cache:generate' | 'simplecache:generate', 'css'
	 *
	 * @return string|null View content
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$options = $hook->getParam('compiler_options', []);
		return _elgg_services()->cssCompiler->compile($hook->getValue(), $options);
	}
}
