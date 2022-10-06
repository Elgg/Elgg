<?php

namespace Elgg\Groups;

use Elgg\Collections\Collection;
use Elgg\EventsService;

/**
 * Group tools service
 *
 * NOTE: This is part of the public Elgg Service DI
 */
class Tools {

	/**
	 * @var Collection
	 */
	protected $tools;

	/**
	 * @var EventsService
	 */
	protected $events;

	/**
	 * Constructor
	 *
	 * @param EventsService $events Events
	 */
	public function __construct(EventsService $events) {
		$this->tools = new Collection([], Tool::class);
		$this->events = $events;
	}

	/**
	 * Adds a group tool option
	 *
	 * @param string $name    Tool ID
	 * @param array  $options Tool config options
	 *
	 * @option string   $label      Label to appear on the group edit form
	 * @option string   $title      Tool name
	 * @option bool     $default_on Is the tool enabled by default?
	 * @option int      $priority   Module priority
	 *
	 * @return void
	 */
	public function register($name, array $options = []) {
		$tool = new Tool($name, $options);

		$this->tools->add($tool);
	}

	/**
	 * Removes a group tool
	 *
	 * @param string $name Tool name
	 *
	 * @return void
	 */
	public function unregister($name) {
		$this->tools->remove($name);
	}

	/**
	 * Get a registered tool by its name
	 *
	 * @param string $name Tool name
	 * @return Tool|null
	 */
	public function get($name) {
		return $this->all()->get($name);
	}

	/**
	 * Returns registered tools
	 *
	 * @return Collection|Tool[]
	 */
	public function all() {
		$tool_options = clone $this->tools;
		
		return $this->events->triggerResults('tool_options', 'group', [], $tool_options);
	}

	/**
	 * Returns group specific tools
	 *
	 * @param \ElggGroup $group Group
	 *
	 * @return Collection|Tool[]
	 */
	public function group(\ElggGroup $group) {

		$tool_options = clone $this->tools;

		$params = [
			'entity' => $group,
		];

		return $this->events->triggerResults('tool_options', 'group', $params, $tool_options);
	}
}
