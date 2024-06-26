<?php

namespace Elgg\Groups;

use Elgg\Collections\CollectionItemInterface;

/**
 * Represents a registered group tool option
 *
 * @property string      $name          Module name
 * @property string      $label         Module title
 * @property bool        $default_on    Enabled by default
 * @property int         $priority      Priority
 */
class Tool implements CollectionItemInterface {

	protected array $options;

	/**
	 * Constructor
	 *
	 * @param string $name    Tool name
	 * @param array  $options Tool options
	 */
	public function __construct(public string $name, array $options = []) {
		$defaults = [
			'label' => null,
			'default_on' => true,
			'priority' => 500,
		];

		$this->options = array_merge($defaults, $options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __get($name) {
		switch ($name) {
			case 'label':
				return $this->getLabel();

			case 'default_on':
				return $this->isEnabledByDefault();
		}

		return elgg_extract($name, $this->options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __set($name, $value) {
		$this->options[$name] = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getID() {
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * Get tool label
	 *
	 * @return string
	 */
	public function getLabel(): string {
		$label = elgg_extract('label', $this->options);
		if (isset($label)) {
			return $label;
		}

		return elgg_echo("groups:tool:{$this->name}");
	}

	/**
	 * Get tool description
	 *
	 * @return string|null
	 */
	public function getDescription(): ?string {
		$lan_key = "groups:tool:{$this->name}:description";
		if (!elgg_language_key_exists($lan_key)) {
			return null;
		}
		
		return elgg_echo($lan_key);
	}

	/**
	 * Is the tool enabled by default?
	 *
	 * @return bool
	 */
	public function isEnabledByDefault(): bool {
		$default_on = elgg_extract('default_on', $this->options, true);

		if ($default_on === true || $default_on === 'yes') {
			return true;
		}

		return false;
	}

	/**
	 * Get metadata name for DB storage
	 *
	 * @return string
	 */
	public function mapMetadataName(): string {
		return "{$this->name}_enable";
	}

	/**
	 * Get metadata value for DB storage
	 *
	 * @param mixed $value Input value
	 *                     Defaults to 'default_on' property
	 *
	 * @return string
	 */
	public function mapMetadataValue($value = null): string {
		if (!isset($value)) {
			$value = $this->default_on;
		}

		if ($value === 'yes' || $value === true) {
			return 'yes';
		}

		return 'no';
	}
}
