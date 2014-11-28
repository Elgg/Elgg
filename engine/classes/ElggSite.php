<?php
/**
 * A Site entity.
 *
 * ElggSite represents a single site entity.
 *
 * An ElggSite object is an ElggEntity child class with the subtype
 * of "site."  It is created upon installation and holds information about a site:
 *  - name
 *  - description
 *  - url
 *
 * Every ElggEntity belongs to a site.
 *
 * @internal ElggSite represents a single row from the sites_entity
 * table, as well as the corresponding ElggEntity row from the entities table.
 *
 * @warning Multiple site support isn't fully developed.
 *
 * @package    Elgg.Core
 * @subpackage DataMode.Site
 * @link       http://learn.elgg.org/en/stable/design/database.html
 *
 * @property string $name        The name or title of the website
 * @property string $description A motto, mission statement, or description of the website
 * @property string $url         The root web address for the site, including trailing slash
 */
class ElggSite extends ElggEntity {

	/**
	 * Initialize the attributes array.
	 * This is vital to distinguish between metadata and base attributes.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['type'] = "site";
		$this->attributes['name'] = null;
		$this->attributes['description'] = null;
		$this->attributes['url'] = null;
		$this->tables_split = 2;
	}

	/**
	 * Create a new ElggSite.
	 *
	 * Plugin developers should only use the constructor to create a new entity.
	 * To retrieve entities, use get_entity() and the elgg_get_entities* functions.
	 *
	 * @param stdClass $row Database row result. Default is null to create a new site.
	 *
	 * @throws IOException If cannot load remaining data from db
	 * @throws InvalidParameterException If not passed a db result
	 */
	public function __construct($row = null) {
		$this->initializeAttributes();

		// compatibility for 1.7 api.
		$this->initialise_attributes(false);

		if (!empty($row)) {
			// Is $row is a DB entity table row
			if ($row instanceof stdClass) {
				// Load the rest
				if (!$this->load($row)) {
					$msg = "Failed to load new " . get_class() . " for GUID:" . $row->guid;
					throw new IOException($msg);
				}
			} else if ($row instanceof ElggSite) {
				// $row is an ElggSite so this is a copy constructor
				elgg_deprecated_notice('This type of usage of the ElggSite constructor was deprecated. Please use the clone method.', 1.7);
				foreach ($row->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}
			} else if (strpos($row, "http") !== false) {
				// url so retrieve by url
				elgg_deprecated_notice("Passing URL to constructor is deprecated. Use get_site_by_url()", 1.9);
				$row = get_site_by_url($row);
				foreach ($row->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}
			} else if (is_numeric($row)) {
				// $row is a GUID so load
				elgg_deprecated_notice('Passing a GUID to constructor is deprecated. Use get_entity()', 1.9);
				if (!$this->load($row)) {
					throw new IOException("Failed to load new " . get_class() . " from GUID:" . $row);
				}
			} else {
				throw new InvalidParameterException("Unrecognized value passed to constuctor.");
			}
		}
	}

	/**
	 * Loads the full ElggSite when given a guid.
	 *
	 * @param mixed $guid GUID of ElggSite entity or database row object
	 *
	 * @return bool
	 * @throws InvalidClassException
	 */
	protected function load($guid) {
		$attr_loader = new Elgg_AttributeLoader(get_class(), 'site', $this->attributes);
		$attr_loader->requires_access_control = !($this instanceof ElggPlugin);
		$attr_loader->secondary_loader = 'get_site_entity_as_row';

		$attrs = $attr_loader->getRequiredAttributes($guid);
		if (!$attrs) {
			return false;
		}

		$this->attributes = $attrs;
		$this->tables_loaded = 2;
		$this->loadAdditionalSelectValues($attr_loader->getAdditionalSelectValues());
		_elgg_cache_entity($this);

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function create() {
		global $CONFIG;

		$guid = parent::create();

		$name = sanitize_string($this->attributes['name']);
		$description = sanitize_string($this->attributes['description']);
		$url = sanitize_string($this->attributes['url']);

		$query = "INSERT into {$CONFIG->dbprefix}sites_entity
			(guid, name, description, url) values ($guid, '$name', '$description', '$url')";

		$result = $this->getDatabase()->insertData($query);
		if ($result === false) {
			// TODO(evan): Throw an exception here?
			return false;
		}

		// make sure the site guid is set to self if not already set
		if (!$this->site_guid) {
			$this->site_guid = $guid;
			$this->getDatabase()->updateData("UPDATE {$CONFIG->dbprefix}entities
				SET site_guid = $guid WHERE guid = $guid");
		}

		return $guid;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function update() {
		global $CONFIG;

		if (!parent::update()) {
			return false;
		}

		$guid = (int)$this->guid;
		$name = sanitize_string($this->name);
		$description = sanitize_string($this->description);
		$url = sanitize_string($this->url);

		$query = "UPDATE {$CONFIG->dbprefix}sites_entity
			SET name='$name', description='$description', url='$url' WHERE guid=$guid";

		return $this->getDatabase()->updateData($query) !== false;
	}

	/**
	 * Delete the site.
	 *
	 * @note You cannot delete the current site.
	 *
	 * @return bool
	 * @throws SecurityException
	 */
	public function delete() {
		global $CONFIG;
		if ($CONFIG->site->getGUID() == $this->guid) {
			throw new SecurityException('You cannot delete the current site');
		}

		return parent::delete();
	}

	/**
	 * Disable the site
	 *
	 * @note You cannot disable the current site.
	 *
	 * @param string $reason    Optional reason for disabling
	 * @param bool   $recursive Recursively disable all contained entities?
	 *
	 * @return bool
	 * @throws SecurityException
	 */
	public function disable($reason = "", $recursive = true) {
		global $CONFIG;

		if ($CONFIG->site->getGUID() == $this->guid) {
			throw new SecurityException('You cannot disable the current site');
		}

		return parent::disable($reason, $recursive);
	}

	/**
	 * Returns the URL for this site
	 *
	 * @return string The URL
	 */
	public function getURL() {
		return $this->url;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDisplayName() {
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setDisplayName($displayName) {
		$this->name = $displayName;
	}

	/**
	 * Gets an array of ElggUser entities who are members of the site.
	 *
	 * @param array $options An associative array for key => value parameters
	 *                       accepted by elgg_get_entities(). Common parameters
	 *                       include 'limit', and 'offset'.
	 *                       Note: this was $limit before version 1.8
	 * @param int   $offset  Offset @deprecated parameter
	 *
	 * @return array of ElggUsers
	 * @deprecated 1.9 Use ElggSite::getEntities()
	 */
	public function getMembers($options = array(), $offset = 0) {
		elgg_deprecated_notice('ElggSite::getMembers() is deprecated. Use ElggSite::getEntities()', 1.9);
		if (!is_array($options)) {
			elgg_deprecated_notice("ElggSite::getMembers uses different arguments!", 1.8);
			$options = array(
				'limit' => $options,
				'offset' => $offset,
			);
		}

		$defaults = array(
			'site_guids' => ELGG_ENTITIES_ANY_VALUE,
			'relationship' => 'member_of_site',
			'relationship_guid' => $this->getGUID(),
			'inverse_relationship' => true,
			'type' => 'user',
		);

		$options = array_merge($defaults, $options);

		return elgg_get_entities_from_relationship($options);
	}

	/**
	 * List the members of this site
	 *
	 * @param array $options An associative array for key => value parameters
	 *                       accepted by elgg_list_entities(). Common parameters
	 *                       include 'full_view', 'limit', and 'offset'.
	 *
	 * @return string
	 * @since 1.8.0
	 * @deprecated 1.9 Use elgg_list_entities_from_relationship()
	 */
	public function listMembers($options = array()) {
		elgg_deprecated_notice('ElggSite::listMembers() is deprecated. Use elgg_list_entities_from_relationship()', 1.9);
		$defaults = array(
			'site_guids' => ELGG_ENTITIES_ANY_VALUE,
			'relationship' => 'member_of_site',
			'relationship_guid' => $this->getGUID(),
			'inverse_relationship' => true,
			'type' => 'user',
		);

		$options = array_merge($defaults, $options);

		return elgg_list_entities_from_relationship($options);
	}

	/**
	 * Adds an entity to the site.
	 *
	 * This adds a 'member_of_site' relationship between between the entity and
	 * the site. It does not change the site_guid of the entity.
	 *
	 * @param ElggEntity $entity User, group, or object entity
	 *
	 * @return bool
	 */
	public function addEntity(ElggEntity $entity) {
		if (elgg_instanceof($entity, 'site')) {
			return false;
		}
		return add_entity_relationship($entity->guid, "member_of_site", $this->guid);
	}

	/**
	 * Removes an entity from this site
	 *
	 * @param ElggEntity $entity User, group, or object entity
	 *
	 * @return bool
	 */
	public function removeEntity($entity) {
		if (elgg_instanceof($entity, 'site')) {
			return false;
		}
		return remove_entity_relationship($entity->guid, "member_of_site", $this->guid);
	}

	/**
	 * Get an array of entities that belong to the site.
	 *
	 * This only returns entities that have been explicitly added to the
	 * site through addEntity().
	 *
	 * @param array $options Options array for elgg_get_entities_from_relationship()
	 *                       Parameters set automatically by this method:
	 *                       'relationship', 'relationship_guid', 'inverse_relationship'
	 * @return array
	 */
	public function getEntities(array $options = array()) {
		$options['relationship'] = 'member_of_site';
		$options['relationship_guid'] = $this->guid;
		$options['inverse_relationship'] = true;
		if (!isset($options['site_guid']) || !isset($options['site_guids'])) {
			$options['site_guids'] = ELGG_ENTITIES_ANY_VALUE;
		}

		return elgg_get_entities_from_relationship($options);
	}

	/**
	 * Adds a user to the site.
	 *
	 * @param int $user_guid GUID
	 *
	 * @return bool
	 * @deprecated 1.9 Use ElggSite::addEntity()
	 */
	public function addUser($user_guid) {
		elgg_deprecated_notice('ElggSite::addUser() is deprecated. Use ElggEntity::addEntity()', 1.9);
		return add_site_user($this->getGUID(), $user_guid);
	}

	/**
	 * Removes a user from the site.
	 *
	 * @param int $user_guid GUID
	 *
	 * @return bool
	 * @deprecated 1.9 Use ElggSite::removeEntity()
	 */
	public function removeUser($user_guid) {
		elgg_deprecated_notice('ElggSite::removeUser() is deprecated. Use ElggEntity::removeEntity()', 1.9);
		return remove_site_user($this->getGUID(), $user_guid);
	}

	/**
	 * Returns an array of ElggObject entities that belong to the site.
	 *
	 * @warning This only returns objects that have been explicitly added to the
	 * site through addObject()
	 *
	 * @param string $subtype Entity subtype
	 * @param int    $limit   Limit
	 * @param int    $offset  Offset
	 *
	 * @return array
	 * @deprecated 1.9 Use ElggSite:getEntities()
	 */
	public function getObjects($subtype = "", $limit = 10, $offset = 0) {
		elgg_deprecated_notice('ElggSite::getObjects() is deprecated. Use ElggSite::getEntities()', 1.9);
		return get_site_objects($this->getGUID(), $subtype, $limit, $offset);
	}

	/**
	 * Adds an object to the site.
	 *
	 * @param int $object_guid GUID
	 *
	 * @return bool
	 * @deprecated 1.9 Use ElggSite::addEntity()
	 */
	public function addObject($object_guid) {
		elgg_deprecated_notice('ElggSite::addObject() is deprecated. Use ElggEntity::addEntity()', 1.9);
		return add_site_object($this->getGUID(), $object_guid);
	}

	/**
	 * Remvoes an object from the site.
	 *
	 * @param int $object_guid GUID
	 *
	 * @return bool
	 * @deprecated 1.9 Use ElggSite::removeEntity()
	 */
	public function removeObject($object_guid) {
		elgg_deprecated_notice('ElggSite::removeObject() is deprecated. Use ElggEntity::removeEntity()', 1.9);
		return remove_site_object($this->getGUID(), $object_guid);
	}

	/**
	 * Get the collections associated with a site.
	 *
	 * @param string $subtype Subtype
	 * @param int    $limit   Limit
	 * @param int    $offset  Offset
	 *
	 * @return unknown
	 * @deprecated 1.8 Was never implemented
	 */
	public function getCollections($subtype = "", $limit = 10, $offset = 0) {
		elgg_deprecated_notice("ElggSite::getCollections() is deprecated", 1.8);
		get_site_collections($this->getGUID(), $subtype, $limit, $offset);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function prepareObject($object) {
		$object = parent::prepareObject($object);
		$object->name = $this->getDisplayName();
		$object->description = $this->description;
		unset($object->read_access);
		return $object;
	}

	/*
	 * EXPORTABLE INTERFACE
	 */

	/**
	 * Return an array of fields which can be exported.
	 *
	 * @return array
	 * @deprecated 1.9 Use toObject()
	 */
	public function getExportableValues() {
		return array_merge(parent::getExportableValues(), array(
			'name',
			'description',
			'url',
		));
	}

	/**
	 * Get the domain for this site
	 *
	 * @return string
	 * @since 1.9
	 */
	public function getDomain() {
		$breakdown = parse_url($this->url);
		return $breakdown['host'];
	}

	/**
	 * Halts bootup and redirects to the site front page
	 * if site is in walled garden mode, no user is logged in,
	 * and the URL is not a public page.
	 *
	 * @return void
	 * @since 1.8.0
	 */
	public function checkWalledGarden() {
		global $CONFIG;

		// command line calls should not invoke the walled garden check
		if (PHP_SAPI === 'cli') {
			return;
		}

		if ($CONFIG->walled_garden) {
			if ($CONFIG->default_access == ACCESS_PUBLIC) {
				$CONFIG->default_access = ACCESS_LOGGED_IN;
			}
			elgg_register_plugin_hook_handler(
					'access:collections:write',
					'all',
					'_elgg_walled_garden_remove_public_access',
					9999);

			if (!elgg_is_logged_in()) {
				// override the front page
				elgg_register_page_handler('', '_elgg_walled_garden_index');

				if (!$this->isPublicPage()) {
					if (!elgg_is_xhr()) {
						_elgg_services()->session->set('last_forward_from', current_page_url());
					}
					register_error(elgg_echo('loggedinrequired'));
					forward('', 'walled_garden');
				}
			}
		}
	}

	/**
	 * Returns if a URL is public for this site when in Walled Garden mode.
	 *
	 * Pages are registered to be public by {@elgg_plugin_hook public_pages walled_garden}.
	 *
	 * @param string $url Defaults to the current URL.
	 *
	 * @return bool
	 * @since 1.8.0
	 */
	public function isPublicPage($url = '') {
		global $CONFIG;

		if (empty($url)) {
			$url = current_page_url();

			// do not check against URL queries
			if ($pos = strpos($url, '?')) {
				$url = substr($url, 0, $pos);
			}
		}

		// always allow index page
		if ($url == elgg_get_site_url($this->guid)) {
			return true;
		}

		// default public pages
		$defaults = array(
			'walled_garden/.*',
			'action/.*',
			'login',
			'register',
			'forgotpassword',
			'changepassword',
			'refresh_token',
			'ajax/view/js/languages',
			'upgrade\.php',
			'css/.*',
			'js/.*',
			'cache/[0-9]+/\w+/js|css/.*',
			'cron/.*',
			'services/.*',
		);

		// include a hook for plugin authors to include public pages
		$plugins = elgg_trigger_plugin_hook('public_pages', 'walled_garden', null, array());

		// allow public pages
		foreach (array_merge($defaults, $plugins) as $public) {
			$pattern = "`^{$CONFIG->url}$public/*$`i";
			if (preg_match($pattern, $url)) {
				return true;
			}
		}

		// non-public page
		return false;
	}
}
