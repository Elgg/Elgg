<?php

namespace Elgg\Groups;

use Elgg\Collections\Collection;
use Elgg\PluginHooksService;
use ElggGroup;

/**
 * Group tools service
 *
 * @access private
 * @internal
 */
class Tools {

	/**
	 * @var Collection
	 */
	protected $tools;

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * Constructor
	 *
	 * @param PluginHooksService $hooks Hooks
	 */
	public function __construct(PluginHooksService $hooks) {
		$this->tools = new Collection([], Tool::class);
		$this->hooks = $hooks;
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
		return $this->tools->get($name);
	}

	/**
	 * Returns registered tools
	 *
	 * @return Collection|Tool[]
	 */
	public function all() {
		$tool_options = clone $this->tools;
		
		return $this->hooks->trigger('tool_options', 'group', [], $tool_options);
	}

	/**
	 * Returns group specific tools
	 *
	 * @param ElggGroup $group Group
	 *
	 * @return Collection|Tool[]
	 */
	public function group(ElggGroup $group) {

		$tool_options = clone $this->tools;

		$params = [
			'entity' => $group,
		];

		return $this->hooks->trigger('tool_options', 'group', $params, $tool_options);
	}
}