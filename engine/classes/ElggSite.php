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

		$this->attributes['type'] = 'site';
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
			
			$version = elgg_get_config('version');
			if (!empty($version) && $version < 2016112500) {
				// sites_entity table still exists
				// load all extra table data to be able to boot/upgrade
				$db = $this->getDatabase();
				$query = "SELECT url FROM {$db->prefix}sites_entity WHERE guid = {$this->guid}";
				$site_data = $db->getDataRow($query);
				if ($site_data) {
					$this->url = $site_data->url;
				}
			}
		}
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
		if (elgg_get_site_entity()->guid == $this->guid) {
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
		if (elgg_get_site_entity()->guid == $this->guid) {
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
