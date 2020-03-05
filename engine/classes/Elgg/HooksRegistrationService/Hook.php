<?php

namespace Elgg\HooksRegistrationService;

use Elgg\Di\PublicContainer;

/**
 * The object passed to invokable class name handlers
 *
 * @internal
 */
class Hook implements \Elgg\Hook {

	private $dic;
	private $name;
	private $type;
	private $value;
	private $params;

	const EVENT_TYPE = 'hook';

	/**
	 * Constructor
	 *
	 * @param PublicContainer $dic    DI container
	 * @param string          $name   Hook name
	 * @param string          $type   Hook type
	 * @param mixed           $value  Hook value
	 * @param mixed           $params Hook params
	 */
	public function __construct(PublicContainer $dic, $name, $type, $value, $params) {
		$this->dic = $dic;
		$this->name = $name;
		$this->type = $type;
		$this->value = $value;
		$this->params = $params;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * {@inheritdoc}
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
	public function setValue($value) {
		$this->value = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParam($key, $default = null) {
		if (!is_array($this->params)) {
			return $default;
		}

		return array_key_exists($key, $this->params) ? $this->params[$key] : $default;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityParam() {
		if (isset($this->params['entity']) && $this->params['entity'] instanceof \ElggEntity) {
			return $this->params['entity'];
		}

		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUserParam() {
		if (isset($this->params['user']) && $this->params['user'] instanceof \ElggUser) {
			return $this->params['user'];
		}

		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function elgg() {
		return $this->dic;
	}
}
