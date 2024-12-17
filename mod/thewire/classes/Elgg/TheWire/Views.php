<?php

namespace Elgg\TheWire;

/**
 * View related functions
 */
class Views {
	
	/**
	 * Replaces text with tag links
	 *
	 * @param \Elgg\Event $event 'prepare', 'html'
	 *
	 * @return null|array
	 */
	public static function parseTags(\Elgg\Event $event): ?array {
		$result = $event->getValue();
		
		$options = elgg_extract('options', $result);
		
		if (!elgg_extract('parse_thewire_hashtags', $options, false)) {
			return null;
		}
		
		$html = elgg_extract('html', $result);
		
		$result['html'] = preg_replace_callback(
			'/(^|[^\w])#(\w+[^\s\d[:punct:]\x{2018}-\x{201F}]+\w*)/u',
			function ($matches) {
				$tag = elgg_extract(2, $matches);
				$url = elgg_generate_url('collection:object:thewire:tag', [
					'tag' => $tag,
				]);
				$link = elgg_view_url($url, "#{$tag}");
				
				return elgg_extract(1, $matches) . $link;
			}, $html) ?? $html;
			
		return $result;
	}
}
