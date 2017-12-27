<?php

namespace Elgg\ActionsService;

use Elgg\Application;
use Elgg\Http\Input;

/**
 * The object passed to invokable class name handlers
 *
 * @access private
 */
class Action implements \Elgg\Action {

	const EVENT_TYPE = 'action';

	/**
	 * @var Application
	 */
	protected $elgg;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var Input
	 */
	protected $input;

	/**
	 * Constructor
	 *
	 * @param Application $elgg  Elgg application
	 * @param string      $name  Hook name
	 * @param Input       $input Input service
	 */
	public function __construct(Application $elgg, string $name, Input $input) {
		$this->elgg = $elgg;
		$this->name = $name;
		$this->input = $input;
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
	public function getParams($filter = true) {
		return $this->input->all($filter);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParam($key, $default = null, $filter = true) {
		return $this->input->get($key, $default, $filter);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEntityParam() {
		$guid = $this->input->get('guid');
		if ($guid) {
			$entity = get_entity($guid);
			if ($entity instanceof \ElggEntity) {
				return $entity;
			}
		}

		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUserParam() {
		$guid = $this->input->get('user_guid');
		if ($guid) {
			$entity = get_entity($guid);
			if ($entity instanceof \ElggUser) {
				return $entity;
			}
		}

		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function elgg() {
		return $this->elgg;
	}

}
