<?php

namespace Elgg\File\Forms;

/**
 * Change fields for file upload
 *
 * @since 7.1
 */
class RegisterFields {
	
	/**
	 * Change title/required on file input
	 *
	 * @param \Elgg\Event $event 'forms:register:fields', 'object:file'
	 *
	 * @return array
	 */
	public static function changeFileInput(\Elgg\Event $event): array {
		$entity = $event->getEntityParam();
		
		$fields = $event->getValue();
		foreach ($fields as $index => $field) {
			if (elgg_extract('#type', $field) !== 'file') {
				continue;
			}
			
			if ($entity instanceof \ElggFile) {
				$fields[$index]['value'] = $entity->getFilename();
				$fields[$index]['#label'] = elgg_echo('file:replace');
			} else {
				// new uploads require a file
				$fields[$index]['required'] = true;
			}
			
			break;
		}
		
		return $fields;
	}
}
