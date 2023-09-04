<?php

namespace Elgg\Forms;

/**
 * Prepare the form fields for admin/security/security_txt
 *
 * @since 5.1
 */
class PrepareSecurityTxt {
	
	/**
	 * Prepare the form fields
	 *
	 * @param \Elgg\Event $event 'form:prepare:fields', 'admin/security/security_txt'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event): array {
		$body_vars = $event->getValue();
		
		$fields = [
			'contact',
			'expires',
			'encryption',
			'acknowledgments',
			'language',
			'canonical',
			'policy',
			'hiring',
			'csaf',
		];
		foreach ($fields as $field) {
			$body_vars[$field] = elgg_extract($field, $body_vars, elgg_get_config("security_txt_{$field}"));
		}
		
		return $body_vars;
	}
}
