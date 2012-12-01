<?php

/**
 * Chainable OO API for querying entities (proof of concept)
 *
 * In the following snippet, we're querying for the pages owner's pages listing. We also
 * make some pages sticky (if the collection is available), and allow other plugins to
 * alter the query via hook [query:alter_options, pages:owners_listing].
 *
 * <code>
 * $page_owner = elgg_get_page_owner_entity();
 * $query = new ElggEntitiesQuery();
 * $list = $query->container_guid(elgg_get_page_owner_guid())
 * 				 ->types('object')
 *               ->subtypes('page_top')
 *               ->setName('pages:owners_listing')
 *               ->applyCollection($page_owner, 'pages:sticky', 'sticky')
 *               ->asList()
 *               ->execute();
 * </code>
 *
 * @method ElggEntitiesQuery types($values)
 * @method ElggEntitiesQuery subtypes($value)
 * @method ElggEntitiesQuery type_subtype_pairs($value)
 * @method ElggEntitiesQuery guids($value)
 * @method ElggEntitiesQuery owner_guids($value)
 * @method ElggEntitiesQuery container_guids($value)
 * @method ElggEntitiesQuery site_guids($value)
 * @method ElggEntitiesQuery modified_time_lower($value)
 * @method ElggEntitiesQuery modified_time_upper($value)
 * @method ElggEntitiesQuery created_time_lower($value)
 * @method ElggEntitiesQuery created_time_upper($value)
 * @method ElggEntitiesQuery reverse_order_by($value)
 * @method ElggEntitiesQuery order_by($value)
 * @method ElggEntitiesQuery group_by($value)
 * @method ElggEntitiesQuery limit($value)
 * @method ElggEntitiesQuery offset($value)
 * @method ElggEntitiesQuery count($value)
 * @method ElggEntitiesQuery selects($value)
 * @method ElggEntitiesQuery wheres($value)
 * @method ElggEntitiesQuery joins($value)
 *
 * @access private
 */
class ElggEntitiesQuery implements ElggQueryModifierInterface {

	const COLLECTION_MODEL_STICKY = 'sticky';
	const COLLECTION_MODEL_FILTER = 'filter';
	const COLLECTION_MODEL_SELECTOR = 'selector';

	protected $options;
	protected $as_list = false;
	protected $query_name;
	protected $locked_keys;
	protected $collections = array();

	/**
	 * @var ElggCollectionManager
	 */
	protected $collectionMgr;

	/**
	 * @param array $options
	 */
	public function __construct(array $options = array()) {
		$this->options = $options;
	}

	/**
	 * @see ElggNamedQuery
	 *
	 * @param $query_name
	 */
	public function setName($query_name) {
		$this->query_name = (string) $query_name;
	}

	/**
	 * @see ElggNamedQuery
	 *
	 * @param array|string $locked_keys
	 */
	public function setLockedKeys($locked_keys) {
		$this->locked_keys = (array)$locked_keys;
	}

	/**
	 * Apply a collection to the query
	 *
	 * @param ElggEntity|null $container
	 * @param string $name
	 * @param string $model "sticky", "filter", or "selector"
	 * @return ElggEntitiesQuery
	 * @throws InvalidArgumentException
	 */
	public function applyCollection($container = null, $name, $model) {
		if (!$container) {
			$container = null;
		}
		if (!in_array($model, array('sticky', 'filter', 'selector'))) {
			throw new InvalidArgumentException("Invalid model: $model");
		}
		if (!$this->collectionMgr) {
			$this->setCollectionManager();
		}
		$method = "get" . ucfirst($model) . "Modifier";
		$modifier = $this->collectionMgr->{$method}($container, $name);
		/* @var ElggCollectionQueryModifier $modifier */

		$this->options = $modifier->prepareOptions($this->options);

		return $this;
	}

	/**
	 * Allow injecting collection manager (unit testing)
	 *
	 * @param ElggCollectionManager $mgr
	 *
	 * @access private
	 */
	public function setCollectionManager(ElggCollectionManager $mgr = null) {
		if (!$mgr) {
			$mgr = elgg_collections();
		}
		$this->collectionMgr = $mgr;
	}

	/**
	 * @param bool $list
	 * @return ElggEntitiesQuery
	 */
	public function asList($list = true) {
		$this->as_list = (bool) $list;
		return $this;
	}

	public function __call($name, $args) {
		$methods = array(
			'types',
			'subtypes',
			'type_subtype_pairs',

			'guids',
			'owner_guids',
			'container_guids',
			'site_guids',

			'modified_time_lower',
			'modified_time_upper',
			'created_time_lower',
			'created_time_upper',

			'reverse_order_by',
			'order_by',
			'group_by',
			'limit',
			'offset',
			'count',
			'selects',
			'wheres',
			'joins',
		);
		if (in_array($name, $methods)) {
			if (count($args) < 1) {
				throw new InvalidArgumentException("Method $name requires argument");
			}
			$this->options[$name] = $args[0];
			return $this;
		}
		throw new ErrorException("Method $name does not exist");
	}

	/**
	 * Get the modified $options array for an elgg_get_*() query
	 *
	 * @return array
	 */
	public function getOptions() {
		$options = $this->options;
		if ($this->query_name) {
			$modifier = new ElggNamedQuery($this->query_name, $options, $this->locked_keys);
			$options = $modifier->getOptions();
		}
		return $options;
	}

	/**
	 * @return ElggEntity[]|string
	 */
	public function execute() {
		$options = $this->getOptions();
		if ($this->as_list) {
			return elgg_list_entities($options);
		}
		return elgg_get_entities($options);
	}
}
