<?php
namespace Elgg;

use Elgg\Di\PublicContainer;

/**
 * Models an event passed to event handlers
 *
 * @since 2.0.0
 */
class Event {
	
	/**
	 * @var PublicContainer
	 */
	protected $dic;
	
	/**
	 * @var string
	 */
	protected $name;
	
	/**
	 * @var string
	 */
	protected $type;
	
	/**
	 * @var mixed
	 */
	protected $value;
	
	/**
	 * @var mixed
	 */
	protected $params;
	
	/**
	 * Constructor
	 *
	 * @param PublicContainer $dic    DI container
	 * @param string          $name   Event name
	 * @param string          $type   Event type
	 * @param mixed           $value  Event value
	 * @param mixed           $params Event params
	 */
	public function __construct(PublicContainer $dic, string $name, string $type, $value = null, $params = []) {
		$this->dic = $dic;
		$this->name = $name;
		$this->type = $type;
		$this->value = $value;
		$this->params = $params;
	}
	
	/**
	 * Get the name of the event
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Get the type of the event object
	 *
	 * @return string
	 */
	public function getType(): string {
		return $this->type;
	}

	/**
	 * Get the object of the event
	 *
	 * @return mixed
	 */
	public function getObject() {
		return elgg_extract('object', $this->params);
	}
	
	/**
	 * Get the current value of the event
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}
	
	/**
	 * Update the value
	 *
	 * @param mixed $value The new value
	 *
	 * @return void
	 * @internal
	 */
	public function setValue($value): void {
		$this->value = $value;
	}
	
	/**
	 * Get the parameters passed to the trigger call
	 *
	 * @return mixed
	 */
	public function getParams() {
		return $this->params;
	}
	
	/**
	 * Get an element of the params array. If the params array is not an array,
	 * the default will always be returned.
	 *
	 * @param string $key     The key of the value in the params array
	 * @param mixed  $default The value to return if missing
	 *
	 * @return mixed
	 */
	public function getParam(string $key, $default = null) {
		if (!is_array($this->params)) {
			return $default;
		}
		
		return elgg_extract($key, $this->params, $default);
	}
	
	/**
	 * Gets the "entity" key from the params if it holds an ElggEntity
	 *
	 * @return \ElggEntity|null
	 */
	public function getEntityParam(): ?\ElggEntity {
		if (isset($this->params['entity']) && $this->params['entity'] instanceof \ElggEntity) {
			return $this->params['entity'];
		}
		
		return null;
	}
	
	/**
	 * Gets the "user" key from the params if it holds an ElggUser
	 *
	 * @return \ElggUser|null
	 */
	public function getUserParam(): ?\ElggUser {
		if (isset($this->params['user']) && $this->params['user'] instanceof \ElggUser) {
			return $this->params['user'];
		}
		
		return null;
	}

	/**
	 * Get the DI container
	 *
	 * @return PublicContainer
	 */
	public function elgg(): PublicContainer {
		return $this->dic;
	}
	
	/**
	 * When the event is part of a sequence a unique ID is set for each sequence
	 *
	 * @return string|null
	 */
	public function getSequenceID(): ?string {
		return elgg_extract('_elgg_sequence_id', $this->params);
	}
}
