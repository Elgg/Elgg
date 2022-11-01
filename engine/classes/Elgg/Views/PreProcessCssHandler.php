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
	 * @param \Elgg\Event $event 'cache:generate' | 'simplecache:generate', 'css'
	 *
	 * @return string|null View content
	 */
	public function __invoke(\Elgg\Event $event) {
		$options = $event->getParam('compiler_options', []);
		return _elgg_services()->cssCompiler->compile($event->getValue(), $options);
	}
}
