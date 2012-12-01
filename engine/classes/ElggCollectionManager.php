<?php

/**
 * Use or create collections
 *
 * @todo move the query modifiers into separate class?
 *
 * @see ElggCollection
 */
class ElggCollectionManager {

	/**
	 * @var array cached references to collections
	 */
	protected $instances = array();

	/**
	 * Get a reference to a collection if it exists
	 *
	 * @param ElggEntity $entity
	 * @param string $name
	 * @return ElggCollection|null
	 */
	public function fetch(ElggEntity $entity = null, $name) {
		if (!$entity || !$entity->guid || !$name) {
			return null;
		}
		// common case
		if (ElggCollection::canSeeExistenceMetadata($entity, $name)) {
			return $this->factory($entity, $name, true);
		}
		if (!$entity->canEdit()) {
			return null;
		}
		// This allows us to support hidden/differently owned metadata, but make sure anyone who can
		// edit the entity can always access/edit the collection. (the metadata is just an implementation
		// detail)
		if ($this->exists($entity, $name)) {
			return $this->factory($entity, $name, true);
		}
		return null;
	}

	/**
	 * Does the collection exist? This does not imply the current user can access it.
	 *
	 * @param ElggEntity|int $entity entity or GUID
	 * @param $name
	 * @return bool
	 */
	public function exists($entity, $name) {
		if ($entity instanceof ElggEntity) {
			// check GUID, entity may not be saved
			return ($entity->guid && ElggCollection::canSeeExistenceMetadata($entity, $name));
		}
		$ia = elgg_set_ignore_access(true);
		$entity = get_entity($entity);
		$exists = ($entity && ElggCollection::canSeeExistenceMetadata($entity, $name));
		elgg_set_ignore_access($ia);
		return $exists;
	}

	/**
	 * Create (or fetch an existing) named collection on an entity. Good for creating a collection
	 * on demand for editing.
	 *
	 * @param ElggEntity $entity
	 * @param string $name
	 * @return ElggCollection|bool false if user is not permitted to create
	 */
	public function create(ElggEntity $entity, $name) {
		// check GUID, entity may not be saved
		if ($entity->guid && $entity->canEdit()) {
			$coll = $this->fetch($entity, $name);
			if (!$coll) {
				$coll = $this->factory($entity, $name, false);
			}
			return $coll;
		}
		return false;
	}

	/**
	 * Get an object used to implement sticky items
	 *
	 * @param ElggEntity $container
	 * @param string $name
	 * @return ElggCollectionQueryModifier
	 */
	public function getStickyModifier(ElggEntity $container, $name) {
		$collection = $this->fetch($container, $name);
		$application = new ElggCollectionQueryModifier($collection);
		return $application->useStickyModel();
	}

	/**
	 * Get an object used to filter out collection items
	 *
	 * @param ElggEntity $container
	 * @param string $name
	 * @return ElggCollectionQueryModifier
	 */
	public function getFilterModifier(ElggEntity $container, $name) {
		$collection = $this->fetch($container, $name);
		$application = new ElggCollectionQueryModifier($collection);
		return $application->useAsFilter();
	}

	/**
	 * Get an object used to select only items from a collection
	 *
	 * @param ElggEntity $container
	 * @param string $name
	 * @return ElggCollectionQueryModifier
	 */
	public function getSelectorModifier(ElggEntity $container, $name) {
		$collection = $this->fetch($container, $name);
		return new ElggCollectionQueryModifier($collection);
	}

	/**
	 * Trigger the ("apply", "xcollection") plugin hook to apply collections to an elgg_get_entities
	 * query.
	 *
	 * $params will, by default, contain:
	 *    query_name : name that hook handlers will look for to apply their collections.
	 *                 e.g. "pages_group_widget_list"
	 *    options    : a copy of the $options array (but without the "xcollections" key)
	 *    function   : "elgg_get_entities"
	 *
	 * $returnValue will contain a (possibly empty) array of ElggCollectionQueryModifier objects to
	 * which the handler should push their own ElggCollectionQueryModifier object(s), or alter those
	 * already added.
	 *
	 * @param array $options to be passed into elgg_get_entities
	 * @param string $query_name a name that hook handlers can recognize the query by
	 * @param array $params to be passed to the hook handler
	 */
	public function alterEntitiesQuery(&$options, $query_name, array $params = array()) {
		$this->triggerHooks($options, $query_name, $params, 'elgg_get_entities');
	}

	/**
	 * Trigger the ("apply", "xcollection") plugin hook to apply collections to an elgg_get_river
	 * query.
	 *
	 * $params will, by default, contain:
	 *    query_name : name that hook handlers will look for to apply their collections.
	 *                 e.g. "activity_stream"
	 *    options    : a copy of the $options array (but without the "xcollections" key)
	 *    function   : "elgg_get_river"
	 *
	 * $returnValue will contain a (possibly empty) array of ElggCollectionQueryModifier objects to
	 * which the handler should push their own ElggCollectionQueryModifier object(s), or alter those
	 * already added.
	 *
	 * @param array $options to be passed into elgg_get_river
	 * @param string $query_name a name that hook handlers can recognize the query by
	 * @param array $params to be passed to the hook handler
	 *
	 * @access private
	 */
	public function alterRiverQuery(&$options, $query_name, array $params = array()) {
		$this->triggerHooks($options, $query_name, $params, 'elgg_get_river');
	}

	/**
	 * This is a shim to support a 'collections' key in $options for elgg_get_entities, etc.
	 * Call this on $options to convert 'collections' into other keys that those functions
	 * already support.
	 *
	 * @param array $options
	 * @param string $join_column (e.g. set to "rv.id" to order river items)
	 */
	protected function applyCollectionsToOptions(&$options, $join_column = 'e.guid') {
		if (empty($options['xcollections'])) {
			return;
		}
		if (! is_array($options['xcollections'])) {
			$options['xcollections'] = array($options['xcollections']);
		}
		foreach ($options['xcollections'] as $app) {
			// convert a raw collection to a query modifier
			if ($app instanceof ElggCollection) {
				$app = new ElggCollectionQueryModifier($app);
			}
			if ($app instanceof ElggCollectionQueryModifier) {
				$options = $app->prepareOptions($options, $join_column);
			}
		}
		ElggCollectionQueryModifier::resetCounter();
		unset($options['xcollections']);
	}

	/**
	 * This is a shim to support a 'collections' key in $options for elgg_get_river.
	 * Call this on $options to convert 'collections' into other keys that get_river
	 * already supports.
	 *
	 * @param $options
	 */
	protected function applyCollectionsToRiverOptions(&$options) {
		$this->applyCollectionsToOptions($options, 'rv.id');
	}


	/**
	 * @param array $options passed by reference
	 * @param string $query_name
	 * @param array $params
	 * @param string $func
	 */
	protected function triggerHooks(&$options, $query_name, $params, $func = 'elgg_get_entities') {
		$params = array_merge($params, array(
			'query_name' => $query_name,
			'function' => $func,
			'options' => $options,
		));
		unset($params['options']['xcollections']);
		if (empty($options['xcollections'])) {
			$options['xcollections'] = array();
		}
		$options['xcollections'] = elgg_trigger_plugin_hook('apply', 'xcollection', $params, $options['xcollections']);
		$this->applyCollectionsToOptions($options, $this->getJoinColumn($func));
	}

	/**
	 * Get the column expression to join the items column to. e.g. "rv.id"
	 *
	 * @param string $query_function name of Elgg query function (e.g. elgg_get_entities)
	 * @return string
	 */
	protected function getJoinColumn($query_function) {
		if (false !== strpos($query_function, 'river')) {
			return 'rv.id';
		}
		return 'e.guid';
	}

	/**
	 * Makes sure only one instance is handed out of each possible collection
	 *
	 * @param ElggEntity $entity
	 * @param string $name
	 * @param bool $has_metadata
	 * @return ElggCollection
	 *
	 * @access private
	 */
	protected function factory(ElggEntity $entity, $name, $has_metadata = false) {
		$key = $entity->guid . '|' . $name;
		if (!isset($this->instances[$key])) {
			$this->instances[$key] = new ElggCollection($entity, $name, $has_metadata);
		}
		return $this->instances[$key];
	}
}
