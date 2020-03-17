<?php

namespace Elgg\Javascript;

/**
 * Adds lightbox config to js elgg.data object
 *
 * @since 4.0
 */
class SetLightboxConfigHandler {
	
	/**
	 * Set lightbox config
	 *
	 * @param \Elgg\Hook $hook 'elgg.data', 'site'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$return = $hook->getValue();
	
		$return['lightbox'] = [
			'current' => elgg_echo('js:lightbox:current', ['{current}', '{total}']),
			'previous' => elgg_view_icon('caret-left'),
			'next' => elgg_view_icon('caret-right'),
			'close' => elgg_view_icon('times'),
			'opacity' => 0.5,
			'maxWidth' => '990px',
			'maxHeight' => '990px',
			'initialWidth' => '300px',
			'initialHeight' => '300px',
		];
	
		return $return;
	}
}
