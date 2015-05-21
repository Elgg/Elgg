<?php
namespace Elgg\HooksRegistrationService;

use Elgg\Application;

/**
 * The object passed to invokable class name handlers
 *
 * @access private
 */
class Event implements
	\Elgg\Event,
	\Elgg\ObjectEvent,
	\Elgg\UserEvent {

	const EVENT_TYPE = 'event';

	private $elgg;
	private $name;
	private $type;
	private $object;

	/**
	 * Constructor
	 *
	 * @param Application $elgg   Elgg application
	 * @param string      $name   Event name
	 * @param string      $type   Event type
	 * @param mixed       $object Object of the event
	 */
	public function __construct(Application $elgg, $name, $type, $object) {
		$this->elgg = $elgg;
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
		return $this->elgg;
	}

	/**
	 * @return array
	 */
	public function toLegacyArgs() {
		return [$this->name, $this->type, $this->object];
	}
}
