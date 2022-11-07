<?php

namespace Elgg\Views;

/**
 * Minifies content
 *
 * @since 4.0
 */
class MinifyHandler {
	
	/**
	 * Minifies simplecache CSS and JS views by handling the 'simplecache:generate' event
	 *
	 * @param \Elgg\Event $event 'simplecache:generate', 'css'
	 *
	 * @return string|null View content minified (if css/js type)
	 */
	public function __invoke(\Elgg\Event $event) {
		if (preg_match('~[\.-]min\.~', (string) $event->getParam('view'))) {
			// bypass minification
			return;
		}

		switch ($event->getType()) {
			case 'js':
				if (!_elgg_services()->config->simplecache_minify_js) {
					break;
				}
				
				$minifier = new \MatthiasMullie\Minify\JS($event->getValue());
				return $minifier->minify();
			case 'css':
				if (!_elgg_services()->config->simplecache_minify_css) {
					break;
				}

				$minifier = new \MatthiasMullie\Minify\CSS($event->getValue());
				return $minifier->minify();
		}
	}
}
