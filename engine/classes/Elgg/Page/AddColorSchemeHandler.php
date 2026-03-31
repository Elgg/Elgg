<?php

namespace Elgg\Page;

/**
 * Add color scheme information to page html
 *
 * @since 7.0
 */
class AddColorSchemeHandler {
	
	/**
	 * Add color scheme information to page html
	 *
	 * @param \Elgg\Event $event 'view_vars', 'page/elements/html'
	 *
	 * @return null|array
	 */
	public function __invoke(\Elgg\Event $event): ?array {
		if (!elgg_get_config('color_schemes_enabled')) {
			return null;
		}
		
		$vars = $event->getValue();
		
		if (elgg_in_context('admin')) {
			// @todo make admin backend support color schemes
			$vars['html_attrs']['data-color-scheme'] = 'default';

			return $vars;
		}
		
		$color_scheme = elgg_get_logged_in_user_entity()?->elgg_color_scheme;
		if (!isset($color_scheme) || $color_scheme === 'browser') {
			return null;
		}

		$vars['html_attrs']['data-color-scheme'] = $color_scheme;
	
		return $vars;
	}
}
