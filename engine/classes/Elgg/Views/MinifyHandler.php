<?php

namespace Elgg\Views;

/**
 * Minifies content
 *
 * @since 4.0
 */
class MinifyHandler {
	
	/**
	 * Minifies simplecache CSS and JS views by handling the 'simplecache:generate' hook
	 *
	 * @param \Elgg\Hook $hook 'simplecache:generate', 'css'
	 *
	 * @return string|null View content minified (if css/js type)
	 */
	public function __invoke(\Elgg\Hook $hook) {
		if (preg_match('~[\.-]min\.~', $hook->getParam('view'))) {
			// bypass minification
			return;
		}

		switch ($hook->getType()) {
			case 'js':
				if (!_elgg_services()->config->simplecache_minify_js) {
					break;
				}
				
				return \JSMin::minify($hook->getValue());
			case 'css':
				if (!_elgg_services()->config->simplecache_minify_css) {
					break;
				}

				$cssmin = new \CSSmin(false);
				return $cssmin->run($hook->getValue());
		}
	}
}
