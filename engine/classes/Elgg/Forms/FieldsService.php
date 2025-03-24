<?php

namespace Elgg\Forms;

use Elgg\Database\EntityTable;
use Elgg\EventsService;
use Elgg\I18n\Translator;
use Elgg\Traits\Loggable;

/**
 * Service for getting field definitions for type/subtype combinations
 *
 * @since 4.0
 */
class FieldsService {

	use Loggable;
	
	protected array $fields = [];

	/**
	 * Constructor
	 *
	 * @param EventsService $events      events service
	 * @param Translator    $translator  translator service
	 * @param EntityTable   $entityTable entity table
	 */
	public function __construct(protected EventsService $events, protected Translator $translator, protected EntityTable $entityTable) {
	}
	
	/**
	 * Returns an array of fields for a give type/subtype combination
	 *
	 * @param string $type    type of the entity to get fields for
	 * @param string $subtype subtype of the entity to get fields for
	 *
	 * @return array
	 */
	public function get(string $type, string $subtype): array {
		if (isset($this->fields[$type][$subtype])) {
			return $this->fields[$type][$subtype];
		}
		
		$entity_class = $this->entityTable->getEntityClass($type, $subtype);
		$defaults = !empty($entity_class) ? $entity_class::getDefaultFields() : [];
		
		$result = (array) $this->events->triggerResults('fields', "{$type}:{$subtype}", [
			'type' => $type,
			'subtype' => $subtype,
		], $defaults);
		
		$fields = [];
		// validate fields and generate labels
		foreach ($result as $field) {
			if (empty($field['name']) || empty($field['#type'])) {
				$this->getLogger()->warning("Field config for '{$type}:{$subtype}' is missing 'name' or '#type' in field: " . print_r($field, true));
				continue;
			}
			
			if (!isset($field['#label'])) {
				$label_key = "fields:{$type}:{$subtype}:{$field['name']}";
				if ($this->translator->languageKeyExists($label_key)) {
					$field['#label'] = $this->translator->translate($label_key);
				}
			}
			
			$fields[] = $field;
		}
		
		$this->fields[$type][$subtype] = $fields;
		
		return $fields;
	}
}
