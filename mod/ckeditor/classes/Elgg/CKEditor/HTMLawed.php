<?php

namespace Elgg\CKEditor;

/**
 * Extends the HTMLawed rules for CKEditor features
 *
 * @since 5.0
 * @internal
 */
class HTMLawed {
	
	/**
	 * Changes htmlawed config
	 *
	 * @param \Elgg\Event $event 'config', 'htmlawed'
	 *
	 * @return array
	 */
	public static function changeConfig(\Elgg\Event $event): array {
		$config = $event->getValue();
		
		$deny_attribute = (string) elgg_extract('deny_attribute', $config);
		$attributes = explode(',', $deny_attribute);
		
		$new_deny = [];
		foreach ($attributes as $attr) {
			$attr = trim($attr);
			if ($attr === 'class') {
				// core adds class as unallowed attribute, but we need it for ckeditor
				continue;
			}
			
			$new_deny[] = $attr;
		}
		
		$config['deny_attribute'] = implode(', ', $new_deny);

		return $config;
	}
}
