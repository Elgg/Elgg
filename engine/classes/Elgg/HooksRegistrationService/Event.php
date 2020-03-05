<?php

namespace Elgg\HooksRegistrationService;

use Elgg\Di\PublicContainer;

/**
 * The object passed to invokable class name handlers
 *
 * @internal
 */
class Event implements
	\Elgg\Event {

	const EVENT_TYPE = 'event';

	private $dic;
	private $name;
	private $type;
	private $object;

	/**
	 * Constructor
	 *
	 * @param PublicContainer $dic    DI container
	 * @param string          $name   Event name
	 * @param string          $type   Event type
	 * @param mixed           $object Object of the event
	 */
	public function __construct(PublicContainer $dic, $name, $type, $object) {
		$this->dic = $dic;
		$this->name = $name;
		$this->type = $type;
		$this->object = $object;
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
	public function getObject() {
		return $this->object;
	}

	/**
	 * {@inheritdoc}
	 */
	public function elgg() {
		return $this->dic;
	}
}
