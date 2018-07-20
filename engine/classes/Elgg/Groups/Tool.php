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

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var array
	 */
	protected $options;

	/**
	 * Constructor
	 *
	 * @param string $name    Tool name
	 * @param array  $options Tool options
	 */
	public function __construct($name, array $options = []) {
		$this->name = $name;

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
			case 'label' :
				return $this->getLabel();

			case 'default_on' :
				return $this->isEnabledByDefault();
		}

		return elgg_extract($name, $this->options);
	}

	/**
	 *
	 */
	public function __set($name, $value) {
		$this->options[$name] = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId() {
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * Get module title
	 * @return string
	 */
	public function getLabel() {
		$label = elgg_extract('label', $this->options);
		if (isset($label)) {
			return $label;
		}

		return elgg_echo("groups:tool:{$this->name}");
	}

	/**
	 * Is the tool enabled by default?
	 * @return bool
	 */
	public function isEnabledByDefault() {
		$default_on = elgg_extract('default_on', $this->options, true);

		if ($default_on === true || $default_on === 'yes') {
			return true;
		}

		return false;
	}

	/**
	 * Get metadata name for DB storage
	 * @return string
	 */
	public function mapMetadataName() {
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
	public function mapMetadataValue($value = null) {
		if (!isset($value)) {
			$value = $this->default_on;
		}

		if ($value === 'yes' || $value === true) {
			return 'yes';
		}

		return 'no';
	}

}