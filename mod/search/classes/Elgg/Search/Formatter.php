<?php

namespace Elgg\Search;

use ElggEntity;
use ElggUser;

/**
 * Populates volatile data with details needed to render a search view
 *
 * @access private
 */
class Formatter {

	/**
	 * @var ElggEntity
	 */
	protected $entity;

	/**
	 * @var array
	 */
	protected $params = [];

	/**
	 * @var Highlighter
	 */
	protected $highlighter;

	/**
	 * Formatter constructor.
	 *
	 * @param ElggEntity $entity  Entity
	 * @param Search     $service Service
	 */
	public function __construct(ElggEntity $entity, Search $service) {
		$this->entity = $entity;
		$this->params = $service->getParams();
		$this->highlighter = $service->getHighlighter();
	}

	/**
	 * Populate search-related volatile data
	 * @return void
	 */
	public function format() {
		_elgg_services()->hooks->trigger('search:format', 'entity', $this->params, $this->entity);

		$this->formatTitle();
		$this->formatDescription();
		$this->formatExtras();
		$this->formatIcon();
		$this->formatURL();
		$this->formatTime();
	}

	/**
	 * Format title
	 * @return void
	 */
	protected function formatTitle() {

		if ($this->entity->getVolatileData('search_matched_title')) {
			return;
		}

		$title = $this->entity->getDisplayName();
		if ($this->entity instanceof ElggUser) {
			$title .= " (@{$this->entity->username})";
		}

		$title = $this->highlighter->highlight($title, 1, 300);
		$this->entity->setVolatileData('search_matched_title', $title);
	}

	/**
	 * Format description
	 * @return void
	 */
	protected function formatDescription() {

		if ($this->entity->getVolatileData('search_matched_description')) {
			return;
		}

		$description = $this->entity->description;
		$description = $this->highlighter->highlight($description, 10, 300);
		$this->entity->setVolatileData('search_matched_description', $description);
	}

	/**
	 * Format extras
	 * @return void
	 */
	protected function formatExtras() {
		if ($this->entity->getVolatileData('search_matched_extra')) {
			return;
		}

		$extra = [];
		$matches = $this->getPropertyMatches();

		foreach ($matches as $property_type => $fields) {
			foreach ($fields as $field => $match) {
				$label = elgg_format_element('strong', [
					'class' => 'search-match-extra-label',
				], $this->getFieldLabel($property_type, $field));


				$extra_row = elgg_format_element('p', [
					'class' => 'elgg-output search-match-extra',
				], $label . ': ' . implode(', ', $match));

				$extra[] = $extra_row;
			}
		}

		$this->entity->setVolatileData('search_matched_extra', implode('', $extra));
	}

	/**
	 * Format entity properties
	 *
	 * @todo Match individual words instead of entire query
	 *
	 * @return array
	 */
	protected function getPropertyMatches() {

		$type = $this->entity->getType();
		$query = elgg_extract('query', $this->params);
		$fields = elgg_extract('fields', $this->params);

		switch ($type) {
			case 'user' :
				$exclude = ['metadata' => ['name', 'username', 'description']];
				break;
			case 'group' :
				$exclude = ['metadata' => ['name', 'description']];
				break;
			case 'object' :
				$exclude = ['metadata' => ['title', 'description']];
				break;
		}

		$matches = [];
		if (!empty($fields)) {
			foreach ($fields as $property_type => $property_type_fields) {
				foreach ($property_type_fields as $field) {
					if (!empty($exclude[$property_type]) && in_array($field, $exclude[$property_type])) {
						continue;
					}

					switch ($property_type) {
						case 'attributes' :
						case 'metadata' :
							$property_values = $this->entity->$field;
							break;

						case 'annotations' :
							$property_values = [];
							$annotations = $this->entity->getAnnotations([
								'annotation_names' => $field,
								'limit' => 0,
							]);
							foreach ($annotations as $annotation) {
								$property_values[] = $annotation->value;
							}
							break;

						case 'private_settings' :
							$property_values = $this->entity->getPrivateSetting($field);
							break;
					}

					if (is_array($property_values)) {
						foreach ($property_values as $text) {
							if (stristr($text, $query)) {
								$matches[$property_type][$field][] = $this->highlighter->highlight($text, 1, 300);
							}
						}
					} else {
						if (stristr($property_values, $query)) {
							$matches[$property_type][$field][] = $this->highlighter->highlight($property_values, 1, 300);
						}
					}
				}
			}
		}

		return $matches;
	}

	/**
	 * Get label for a property
	 *
	 * @param string $property_type Property type
	 * @param string $property_name Property name
	 *
	 * @return string
	 */
	protected function getFieldLabel($property_type, $property_name) {

		$type = $this->entity->getType();
		$subtype = $this->entity->getSubtype();

		$prefix = 'search';

		switch ($type) {
			case 'user' :
				$prefix = 'profile';
				break;
			case 'group' :
				$prefix = 'group';
				break;
			case 'object' :
				$prefix = 'tag_names';
				break;
		}

		$keys = [
			"$type:$subtype:$property_type:field:$property_name",
			"$type:$property_type:$property_name",
			"$prefix:$property_type:$property_name",
			"$type:$subtype:field:$property_name",
			"$type:$property_name",
			"$prefix:$property_name",
		];

		$label = elgg_echo("tag_names:$property_name");

		foreach ($keys as $key) {
			if (elgg_language_key_exists($key)) {
				$label = elgg_echo($key);
				break;
			}
		}

		return $label;
	}

	/**
	 * Format icon
	 * @return void
	 */
	protected function formatIcon() {
		if ($this->entity->getVolatileData('search_icon')) {
			return;
		}

		$type = $this->entity->getType();
		$owner = $this->entity->getOwnerEntity();
		$container = $this->entity->getContainerEntity();

		$size = elgg_extract('size', $this->params, 'small');

		$icon = '';

		if ($this->entity->hasIcon($size) || $this->entity instanceof \ElggFile) {
			$icon = elgg_view_entity_icon($this->entity, $size);
		} else if ($type == 'user' || $type == 'group') {
			$icon = elgg_view_entity_icon($this->entity, $size);
		} else if ($owner instanceof ElggUser) {
			$icon = elgg_view_entity_icon($owner, $size);
		} else if ($container instanceof ElggUser) {
			// display a generic icon if no owner, though there will probably be
			// other problems if the owner can't be found.
			$icon = elgg_view_entity_icon($this->entity, $size);
		}

		$this->entity->setVolatileData('search_icon', $icon);
	}

	/**
	 * Format URL
	 * @return void
	 */
	protected function formatURL() {
		if (!$this->entity->getVolatileData('search_url')) {
			$url = $this->entity->getURL();
			$this->entity->setVolatileData('search_url', $url);
		}
	}

	/**
	 * Format time
	 * @return void
	 */
	protected function formatTime() {
		if (!$this->entity->getVolatileData('search_time')) {
			$this->entity->setVolatileData('search_time', $this->entity->time_created);
		}
	}
}
