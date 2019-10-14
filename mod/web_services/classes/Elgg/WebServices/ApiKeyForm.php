<?php

namespace Elgg\WebServices;

/**
 * Prepare the vars for the api key form
 *
 * @since 3.2
 */
class ApiKeyForm {
	
	/**
	 * @var \ElggApiKey
	 */
	protected $entity;
	
	/**
	 * Build new form helper
	 *
	 * @param \ElggApiKey $entity api key object to edit
	 */
	public function __construct(\ElggApiKey $entity = null) {
		$this->entity = $entity;
	}
	
	/**
	 * Get form values
	 *
	 * @return array
	 */
	public function __invoke() {
		$defaults = [
			'title' => '',
			'description' => '',
		];
		
		// is there an entity to edit
		if ($this->entity instanceof \ElggApiKey) {
			foreach ($defaults as $name => $value) {
				$defaults[$name] = $this->entity->$name;
			}
			
			$defaults['guid'] = $this->entity->guid;
		}
		
		// check sticky form
		$sticky_values = elgg_get_sticky_values('webservices/api_key/edit');
		if (!empty($sticky_values)) {
			foreach ($sticky_values as $name => $value) {
				$defaults[$name] = $value;
			}
			
			elgg_clear_sticky_form('webservices/api_key/edit');
		}
		
		return $defaults;
	}
}
