<?php

namespace Elgg;

use Elgg\Database\Insert;
use Psr\Log\LogLevel;

/**
 * River (activity stream) service
 *
 * API IN FLUX. DO NOT USE DIRECTLY
 *
 * @access private
 * @internal
 */
class RiverService {

	use TimeUsing;
	use Loggable;

	/**
	 * @var array
	 */
	protected $events = [];

	/**
	 * Register a river event
	 *
	 * @param string $action  Event name
	 *                        e.g. publish, create
	 * @param string $type    Event object type
	 *                        e.g. object, group, user, annotation or relationship
	 * @param string $subtype Event object subtype
	 *                        e.g. entity subtype, annotation name or relationship name
	 *
	 * @return void
	 */
	public function registerEvent($action, $type, $subtype = null) {
		if (!isset($subtype)) {
			$subtype = 'all';
		}

		if (!isset($this->events[$type])) {
			$this->events[$type] = [];
		}

		if (!isset($this->events[$type][$subtype])) {
			$this->events[$type][$subtype] = [];
		}

		$this->events[$type][$subtype][$action] = true;
	}

	/**
	 * Unregister river event
	 *
	 * @param string $action  Event name
	 * @param string $type    Event object type
	 * @param string $subtype Event object subtype
	 *
	 * @return void
	 */
	public function unregisterEvent($action, $type, $subtype = null) {
		if (!isset($subtype)) {
			$subtype = 'all';
		}

		unset($this->events[$type][$subtype][$action]);
	}

	/**
	 * Get registered river events
	 * @return array
	 */
	public function getEvents() {
		return $this->events;
	}

	/**
	 * Remove :before and :after suffixes
	 *
	 * @param string $event Event name
	 *
	 * @return string
	 */
	protected function normalizeActionName($event) {
		if (substr($event, -7) == ':before') {
			$event = substr($event, 0, -7);
		}

		if (substr($event, -6) == ':after') {
			$event = substr($event, 0, -6);
		}

		return $event;
	}

	/**
	 * Process registered events and create river items
	 *
	 * @param Event $event Event
	 *
	 * @return void
	 */
	public function handleEvent(\Elgg\Event $event) {

		$object = $event->getObject();

		if (!$object instanceof \ElggData) {
			return;
		}

		$action = $event->getName();
		$type = $object->getType();
		$subtype = $object->getSubtype();

		if (empty($this->events[$type][$subtype][$action])
			&& empty($this->events[$type]['all'][$action])) {
			return;
		}

		$action = $this->normalizeActionName($action);

		$subject = elgg_get_logged_in_user_entity();

		$result = $object;

		if ($object instanceof \ElggComment) {
			$result = $object;

			while ($object instanceof \ElggComment) {
				$object = $result->getContainerEntity();
			}

			if ($action == 'create') {
				$action = $result->getSubtype();
			}
		} else if ($object instanceof \ElggExtender) {
			$result = $object;
			$object = $result->getEntity();
			$action = $result->name;
		} else if ($object instanceof \ElggRelationship) {
			$result = $object;
			$subject = get_entity($result->guid_one);
			$object = get_entity($result->guid_two);
			$action = $result->relationship;
		}

		if (!$subject instanceof \ElggEntity || !$object instanceof \ElggEntity) {
			return;
		}

		$target = null;
		if ($container = $object->getContainerEntity()) {
			if ($container instanceof \ElggUser || $container instanceof \ElggGroup) {
				$target = $container;
			}
		}

		$options = [
			'action' => $action,
			'subject' => $subject,
			'object' => $object,
			'target' => $target,
			'result' => $result,
		];

		$hook_params = [
			'event' => $event,
		];

		$options = elgg_trigger_plugin_hook('prepare', 'river', $hook_params, $options);

		$this->create($options);
	}

	/**
	 * Adds an item to the river.
	 *
	 * @tip    Read the item like "Lisa (subject) reviewed (action)
	 *         Elgg (object) with 5 stars (result) in the group Open Source (target)".
	 *
	 * @param array $options Array in format:
	 *
	 * @option string     $action   Verb describing an action (e.g. pushlish, like, comment)
	 * @option ElggEntity $subject  Subject entity
	 *                              Entity that performed an action
	 *                              Defaults to logged in user
	 * @option ElggEntity $object   Object entity
	 *                              Entity that action was performed on
	 * @option ElggEntity $target   Target entity
	 *                              An entity in whose context the action is performed
	 *                              Usually user or group containing the object
	 * @option ElggData   $result   Result of the action
	 *                              e.g. when user comments on a post, the result is a comment entity
	 *                              when user friends another user, the result is a relationship
	 *                              when user rates a post, the result is the rating annotation
	 * @option int        $posted   The UNIX epoch timestamp of the river item (default: now)
	 *
	 * @return \ElggRiverItem|false
	 */
	public function create(array $options = []) {

		$view = elgg_extract('view', $options, '');
		// use default viewtype for when called from web services api
		if (!empty($view)) {
			elgg_deprecated_notice(
				'Using "view" option in river items is deprecated.
					Instead register a river event and specify a constructor class.',
				'3.0'
			);

			if (!elgg_view_exists($view, 'default')) {
				return false;
			}
		}

		$action = elgg_extract('action', $options);
		if (empty($action)) {
			return false;
		}

		$subject = elgg_extract('subject', $options);
		if (!$subject instanceof \ElggEntity) {
			return false;
		}

		$object = elgg_extract('object', $options);
		if (!$object instanceof \ElggEntity) {
			return false;
		}

		$target = elgg_extract('target', $options);
		if (isset($target) && !$target instanceof \ElggEntity) {
			return false;
		} else if (!isset($target)) {
			$target = $object->getContainerEntity();
		}

		$posted = elgg_extract('posted', $options, $this->getCurrentTime()->getTimestamp());

		$result = elgg_extract('result', $options);
		if (isset($result) && !$result instanceof \ElggData) {
			return false;
		}

		if (!$result) {
			$result = $object;
		}

		$values = [
			'action_type' => $action,
			'view' => $view,
			'subject_guid' => $subject->guid,
			'object_guid' => $object->guid,
			'target_guid' => $target ? $target->guid : 0,
			'result_id' => $result instanceof \ElggEntity ? $result->guid : $result->id,
			'result_type' => $result->getType(),
			'result_subtype' => $result->getSubtype(),
			'annotation_id' => $result instanceof \ElggAnnotation ? $result->id : 0,
			'posted' => $posted,
		];

		$col_types = [
			'action_type' => ELGG_VALUE_STRING,
			'view' => ELGG_VALUE_STRING,
			'subject_guid' => ELGG_VALUE_INTEGER,
			'object_guid' => ELGG_VALUE_INTEGER,
			'target_guid' => ELGG_VALUE_INTEGER,
			'annotation_id' => ELGG_VALUE_INTEGER,
			'result_id' => ELGG_VALUE_INTEGER,
			'result_type' => ELGG_VALUE_STRING,
			'result_subtype' => ELGG_VALUE_STRING,
			'posted' => ELGG_VALUE_INTEGER,
		];

		// return false to stop insert
		$values = elgg_trigger_deprecated_plugin_hook('creating', 'river', null, $values);

		if ($values == false) {
			// inserting did not fail - it was just prevented
			return true;
		}

		try {
			$item = new \ElggRiverItem((object) $values);
		} catch (\Exception $ex) {
			return false;
		}
		
		if (!elgg_trigger_before_event('create', 'river', $item)) {
			return false;
		}

		try {
			$qb = Insert::intoTable('river');
			foreach ($values as $name => $value) {
				$query_params[$name] = $qb->param($value, $col_types[$name]);
			}

			$qb->values($query_params);

			$id = _elgg_services()->db->insertData($qb);
		} catch (\DatabaseException $ex) {
			$this->log(LogLevel::ERROR, $ex);
			return false;
		}

		if (!$id) {
			return false;
		}

		return elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($id) {
			$item = elgg_get_river_item_from_id($id);
			if ($item) {
				elgg_trigger_deprecated_event('created', 'river', $item);
				elgg_trigger_after_event('create', 'river', $item);
			}
			return $item;
		});
	}
}