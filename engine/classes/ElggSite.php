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
			_elgg_services()->hooks->registerHandler(
					'access:collections:write',
					'all',
					'_elgg_walled_garden_remove_public_access',
					9999);

			if (!_elgg_services()->session->isLoggedIn()) {
				// override the front page
				elgg_register_page_handler('', '_elgg_walled_garden_index');

				if (!$this->isPublicPage()) {
					if (!elgg_is_xhr()) {
						_elgg_services()->session->set('last_forward_from', current_page_url());
					}
					register_error(_elgg_services()->translator->translate('loggedinrequired'));
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
		if ($url == _elgg_services()->config->getSiteUrl($this->guid)) {
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
			'ajax/view/languages.js',
			'upgrade\.php',
			'css/.*',
			'js/.*',
			'cache/[0-9]+/\w+/.*',
			'cron/.*',
			'services/.*',
			'serve-file/.*',
			'robots.txt',
			'favicon.ico',
		);

		// include a hook for plugin authors to include public pages
		$plugins = _elgg_services()->hooks->trigger('public_pages', 'walled_garden', null, array());

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
