<?php

namespace Elgg\Forms;

use Elgg\PluginHooksService;
use Elgg\I18n\Translator;
use Elgg\Traits\Loggable;

/**
 * Service for getting field definitions for type/subtype combinations
 *
 * @since 4.0
 */
class FieldsService {

	use Loggable;
	
	/**
	 * @var array
	 */
	protected $fields = [];

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var Translator
	 */
	protected $translator;

	/**
	 * Constructor
	 *
	 * @param PluginHooksService $hooks      hooks service
	 * @param Translator         $translator translator service
	 */
	public function __construct(PluginHooksService $hooks, Translator $translator) {
		$this->hooks = $hooks;
		$this->translator = $translator;
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
		
		$result = (array) $this->hooks->trigger('fields', "{$type}:{$subtype}", [
			'type' => $type,
			'subtype' => $subtype,
		], []);
		
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
