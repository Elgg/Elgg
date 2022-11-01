<?php

namespace Elgg\Pages;

/**
 * Event callbacks for extender
 *
 * @since 4.0
 * @internal
 */
class Extender {

	/**
	 * Override the page annotation url
	 *
	 * @param \Elgg\Event $event 'extender:url', 'annotation'
	 *
	 * @return void|string
	 */
	public static function setRevisionUrl(\Elgg\Event $event) {
		
		$annotation = $event->getParam('extender');
		if ($annotation->getSubtype() == 'page') {
			return elgg_generate_url('revision:object:page', [
				'id' => $annotation->id,
			]);
		}
	}
}
