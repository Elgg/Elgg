<?php
/**
 * A Site entity.
 *
 * \ElggSite represents a single site entity.
 *
 * An \ElggSite object is an \ElggEntity child class with the subtype
 * of "site."  It is created upon installation and holds information about a site:
 *  - name
 *  - description
 *  - url
 *
 * Every \ElggEntity belongs to a site.
 *
 * @note Internal: \ElggSite represents a single row from the sites_entity
 * table, as well as the corresponding \ElggEntity row from the entities table.
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
class ElggSite extends \ElggEntity {

	/**
	 * Initialize the attributes array.
	 * This is vital to distinguish between metadata and base attributes.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['type'] = "site";
		$this->attributes += self::getExternalAttributes();
	}

	/**
	 * Get default values for attributes stored in a separate table
	 *
	 * @return array
	 * @access private
	 *
	 * @see \Elgg\Database\EntityTable::getEntities
	 */
	final public static function getExternalAttributes() {
		return [
			'name' => null,
			'description' => null,
			'url' => null,
		];
	}

	/**
	 * Create a new \ElggSite.
	 *
	 * Plugin developers should only use the constructor to create a new entity.
	 * To retrieve entities, use get_entity() and the elgg_get_entities* functions.
	 *
	 * @param \stdClass $row Database row result. Default is null to create a new site.
	 *
	 * @throws IOException If cannot load remaining data from db
	 * @throws InvalidParameterException If not passed a db result
	 */
	public function __construct(\stdClass $row = null) {
		$this->initializeAttributes();

		if ($row) {
			// Load the rest
			if (!$this->load($row)) {
				$msg = "Failed to load new " . get_class() . " for GUID:" . $row->guid;
				throw new \IOException($msg);
			}
		}
	}

	/**
	 * Loads the full \ElggSite when given a guid.
	 *
	 * @param mixed $guid GUID of \ElggSite entity or database row object
	 *
	 * @return bool
	 * @throws InvalidClassException
	 */
	protected function load($guid) {
		$attr_loader = new \Elgg\AttributeLoader(get_class(), 'site', $this->attributes);
		$attr_loader->requires_access_control = !($this instanceof \ElggPlugin);
		$attr_loader->secondary_loader = 'get_site_entity_as_row';

		$attrs = $attr_loader->getRequiredAttributes($guid);
		if (!$attrs) {
			return false;
		}

		$this->attributes = $attrs;
		$this->loadAdditionalSelectValues($attr_loader->getAdditionalSelectValues());
		_elgg_services()->entityCache->set($this);

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
			throw new \SecurityException('You cannot delete the current site');
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
			throw new \SecurityException('You cannot disable the current site');
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
	 * {@inheritdoc}
	 */
	protected function prepareObject($object) {
		$object = parent::prepareObject($object);
		$object->name = $this->getDisplayName();
		$object->description = $this->description;
		unset($object->read_access);
		return $object;
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
	 * Get the email address for the site
	 *
	 * This can be set in the basic site settings or fallback to noreply@domain
	 *
	 * @return string
	 * @since 3.0.0
	 */
	public function getEmailAddress() {
		$email = $this->email;
		if (empty($email)) {
			$email = "noreply@{$this->getDomain()}";
		}

		return $email;
	}
}
