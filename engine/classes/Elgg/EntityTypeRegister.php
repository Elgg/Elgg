<?php

namespace Elgg;

use Elgg\Database\SubtypeTable;

/**
 * Entity type/subtype registration service
 *
 * @since 2.3
 * @access private
 */
class EntityTypeRegister {

	const NO_SUBTYPE = '__BLANK__';

	/**
	 * @var array
	 */
	private $register = [];

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var SubtypeTable
	 */
	private $subtype_table;

	/**
	 * Constructor
	 * 
	 * @param Config       $config        Config
	 * @param SubtypeTable $subtype_table Subtype table
	 */
	public function __construct(Config $config, SubtypeTable $subtype_table) {
		$this->config = $config;
		$this->subtype_table = $subtype_table;
	}

	/**
	 * Returns configured entity types
	 * @return array
	 */
	public function getTypes() {
		return $this->config->get('entity_types');
	}

	/**
	 * Returns object contructor class name
	 * 
	 * @param string $type    Entity type
	 * @param string $subtype Entity subtype
	 * @return string|false
	 */
	public function getConstructor($type, $subtype = null) {

		$type = strtolower($type);
		if ($subtype) {
			$constructor = $this->subtype_table->getClass($type, $subtype);
			if ($constructor) {
				return $constructor;
			}
		}

		switch ($type) {
			case 'object' :
				return \ElggObject::class;
			case 'user' :
				return \ElggUser::class;
			case 'group' :
				return \ElggGroup::class;
			case 'site' :
				return \ElggSite::class;
		}

		return false;
	}

	/**
	 * Registers an entity type and subtype
	 *
	 * @param string $type        Entity type
	 *                            "object"|"group"|"user"|"site"
	 * @param string $subtype     Entity subtype
	 * @param string $constructor Object constructor class name
	 *                            Class name associated with the entity of this type and subtype
	 *                            Will automatically update subtype, if set value does not match
	 *                            the one in the database
	 *                            @see update_subtype()
	 *                            @see add_subtype()
	 * @param array  $contexts    Context configuration
	 *                            Specifies in which contexts entities of this type and subtype
	 *                            should be considered public-facing, as well as additional options
	 *                            for the given context
	 *                            <code>
	 *                               array(
	 *                                  'search' => true,
	 *                                  'likes' => [
	 *                                     'is_likable' => true,
	 *                                  ],
	 *                                  'edit' => [
	 *                                      'fields' => []
	 *                                  ],
	 *                               )
	 *                            </code>
	 * @return bool
	 */
	public function add($type, $subtype = null, $constructor = null, array $contexts = []) {

		$type = strtolower($type);
		if (!in_array($type, (array) $this->getTypes())) {
			_elgg_services()->logger->error("$type is not a valid entity type");
			return false;
		}

		if (!array_key_exists($type, $this->register)) {
			$this->register[$type] = [];
		}

		$class = $this->getConstructor($type, $subtype);
		if ($subtype) {
			if (!isset($constructor)) {
				$constructor = $class;
			}
			if ($class !== $constructor) {
				if (!$this->subtype_table->update($type, $subtype, $constructor)) {
					$this->subtype_table->add($type, $subtype, $constructor);
				}
			}

			unset($this->register[$type][self::NO_SUBTYPE]);

			if (!empty($this->register[$type][$subtype]['contexts'])) {
				$contexts = array_merge($this->register[$type][$subtype]['contexts'], $contexts);
			}

			$this->register[$type][$subtype] = [
				'constructor' => $constructor,
				'contexts' => (array) $contexts,
			];
		} else {
			$this->register[$type][self::NO_SUBTYPE] = [
				'constructor' => $constructor,
				'contexts' => (array) $contexts,
			];
		}

		return true;
	}

	/**
	 * Unregisters an entity type and subtype
	 *
	 * @warning With a blank subtype, it unregisters that entity type including
	 * all subtypes. If that's the intention, this function must be called after
	 * all subtypes have been registered.
	 *
	 * @param string $type    Entity type
	 *                        "object"|"group"|"user"|"site"
	 * @param string $subtype Entity subtype
	 *                        Leave empty, to unregister both type and all subtypes
	 * @param string $context Context(s)
	 *                        If set, will only unregister entity type from specific context(s)
	 *                        <code>['search', 'likes']</code>
	 * @return bool
	 */
	public function remove($type, $subtype = null, $context = null) {

		$type = strtolower($type);
		if (!in_array($type, (array) $this->getTypes())) {
			_elgg_services()->logger->error("$type is not a valid entity type");
			return false;
		}

		if (empty($this->register[$type])) {
			return false;
		}

		if (!$subtype) {
			$subtype = self::NO_SUBTYPE;
		}

		$contexts = (array) $context;
		foreach ($this->register[$type] as $subtype => $options) {
			if (empty($contexts)) {
				$this->subtype_table->remove($type, $subtype);
				unset($this->register[$type][$subtype]);
				continue;
			}
			foreach ($contexts as $context) {
				unset($this->register[$type][$subtype]['contexts'][$context]);
			}
		}

		return true;
	}

	/**
	 * Returns registered entity types and subtypes
	 * <code>
	 * [
	 *     'object' => [
	 *        'subtype1',
	 *     ],
	 *     'user' => [],
	 * ]
	 * </code>
	 *
	 * @param mixed  $type    Entity type(s)
	 *                        "object"|"group"|"user"|"site"
	 *                        Leave empty for all
	 * @param string $context Context(s)
	 *                        If set, will return subtypes that are registered for ALL contexts
	 *                        <code>['search', 'likes']</code>
	 * @return array
	 */
	public function getSubtypes($type = null, $context = null) {

		if ($type) {
			$types = [strtolower($type)];
		} else {
			$types = array_keys($this->register);
		}

		$register = [];
		foreach ($this->register as $type => $subtypes) {
			if (!in_array($type, $types)) {
				continue;
			}

			foreach ($subtypes as $subtype => $options) {
				if (!$this->inContext($context, $type, $subtype)) {
					continue;
				}
				if ($subtype == self::NO_SUBTYPE) {
					$register[$type] = [];
					continue;
				}
				$register[$type][] = $subtype;
			}
		}

		return $register;
	}

	/**
	 * Check if entity type is considered public in a given context(s)
	 *
	 * @param mixed  $context Context(s)
	 *                        If multiple contexts are provided, will check if entity type is
	 *                        is registered for ALL contexts
	 * @param string $type    Entity type
	 *                        "object"|"group"|"user"|"site"
	 * @param string $subtype Entity subtype
	 * @return bool
	 */
	public function inContext($context, $type, $subtype = null) {
		if (!$subtype) {
			$subtype = self::NO_SUBTYPE;
		}
		if (!isset($this->register[$type][$subtype])) {
			return false;
		}
		$contexts = (array) $context;
		foreach ($contexts as $context) {
			if (empty($this->register[$type][$subtype]['contexts'][$context])) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Returns context config for the entity type
	 * 
	 * @param string $type    Entity type
	 * @param string $subtype Entity subtype
	 * @return mixed
	 */
	public function getContextConfig($type, $subtype = null) {
		$type = strtolower($type);
		if (!$subtype) {
			$subtype = self::NO_SUBTYPE;
		}
		if (!isset($this->register[$type][$subtype]['contexts'])) {
			return false;
		}
		return $this->register[$type][$subtype]['contexts'];
	}
}
