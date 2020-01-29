<?php

namespace Elgg\Pages;

/**
 * Hook callbacks for extender
 *
 * @since 4.0
 * @internal
 */
class Extender {

	/**
	 * Override the page annotation url
	 *
	 * @param \Elgg\Hook $hook 'extender:url', 'annotation'
	 *
	 * @return void|string
	 */
	public static function setRevisionUrl(\Elgg\Hook $hook) {
		
		$annotation = $hook->getParam('extender');
		if ($annotation->getSubtype() == 'page') {
			return elgg_generate_url('revision:object:page', [
				'id' => $annotation->id,
			]);
		}
	}
}
