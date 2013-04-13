<?php
/**
 * A Site entity.
 *
 * ElggSite represents a single site entity.
 *
 * An ElggSite object is an ElggEntity child class with the subtype
 * of "site."  It is created upon installation and hold all the
 * information about a site:
 *  - name
 *  - description
 *  - url
 *
 * Every ElggEntity (except ElggSite) belongs to a site.
 *
 * @internal ElggSite represents a single row from the sites_entity
 * table, as well as the corresponding ElggEntity row from the entities table.
 *
 * @warning Multiple site support isn't fully developed.
 *
 * @package    Elgg.Core
 * @subpackage DataMode.Site
 * @link       http://docs.elgg.org/DataModel/Sites
 * 
 * @property string $name        The name or title of the website
 * @property string $description A motto, mission statement, or description of the website
 * @property string $url         The root web address for the site, including trailing slash
 */
class ElggSite extends ElggEntity {

	/**
	 * Initialise the attributes array.
	 * This is vital to distinguish between metadata and base parameters.
	 *
	 * Place your base parameters here.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['type'] = "site";
		$this->attributes['name'] = NULL;
		$this->attributes['description'] = NULL;
		$this->attributes['url'] = NULL;
		$this->attributes['tables_split'] = 2;
	}

	/**
	 * Load or create a new ElggSite.
	 *
	 * If no arguments are passed, create a new entity.
	 *
	 * If an argument is passed attempt to load a full Site entity.  Arguments
	 * can be:
	 *  - The GUID of a site entity.
	 *  - A URL as stored in ElggSite->url
	 *  - A DB result object with a guid property
	 *
	 * @param mixed $guid If an int, load that GUID.  If a db row then will
	 * load the rest of the data.
	 *
	 * @throws IOException If passed an incorrect guid
	 * @throws InvalidParameterException If passed an Elgg* Entity that isn't an ElggSite
	 */
	function __construct($guid = null) {
		$this->initializeAttributes();

		// compatibility for 1.7 api.
		$this->initialise_attributes(false);

		if (!empty($guid)) {
			// Is $guid is a DB entity table row
			if ($guid instanceof stdClass) {
				// Load the rest
				if (!$this->load($guid)) {
					$msg = elgg_echo('IOException:FailedToLoadGUID', array(get_class(), $guid->guid));
					throw new IOException($msg);
				}
			} else if ($guid instanceof ElggSite) {
				// $guid is an ElggSite so this is a copy constructor
				elgg_deprecated_notice('This type of usage of the ElggSite constructor was deprecated. Please use the clone method.', 1.7);

				foreach ($guid->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}
			} else if ($guid instanceof ElggEntity) {
				// @todo remove and just use else clause
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonElggSite'));
			} else if (strpos($guid, "http") !== false) {
				// url so retrieve by url
				$guid = get_site_by_url($guid);
				foreach ($guid->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}
			} else if (is_numeric($guid)) {
				// $guid is a GUID so load
				if (!$this->load($guid)) {
					throw new IOException(elgg_echo('IOException:FailedToLoadGUID', array(get_class(), $guid)));
				}
			} else {
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnrecognisedValue'));
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
		$attr_loader = new ElggAttributeLoader(get_class(), 'site', $this->attributes);
		$attr_loader->requires_access_control = !($this instanceof ElggPlugin);
		$attr_loader->secondary_loader = 'get_site_entity_as_row';

		$attrs = $attr_loader->getRequiredAttributes($guid);
		if (!$attrs) {
			return false;
		}

		$this->attributes = $attrs;
		$this->attributes['tables_loaded'] = 2;
		_elgg_cache_entity($this);

		return true;
	}

	/**
	 * Saves site-specific attributes.
	 *
	 * @internal Site attributes are saved in the sites_entity table.
	 *
	 * @return bool
	 */
	public function save() {
		global $CONFIG;

		// Save generic stuff
		if (!parent::save()) {
			return false;
		}

		// make sure the site guid is set (if not, set to self)
		if (!$this->get('site_guid')) {
			$guid = $this->get('guid');
			update_data("UPDATE {$CONFIG->dbprefix}entities SET site_guid=$guid
				WHERE guid=$guid");
		}

		// Now save specific stuff
		return create_site_entity($this->get('guid'), $this->get('name'),
			$this->get('description'), $this->get('url'));
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
			throw new SecurityException('SecurityException:deletedisablecurrentsite');
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
			throw new SecurityException('SecurityException:deletedisablecurrentsite');
		}

		return parent::disable($reason, $recursive);
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
	 * @todo remove $offset in 2.0
	 *
	 * @return array of ElggUsers
	 */
	public function getMembers($options = array(), $offset = 0) {
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
			'inverse_relationship' => TRUE,
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
	 */
	public function listMembers($options = array()) {
		$defaults = array(
			'site_guids' => ELGG_ENTITIES_ANY_VALUE,
			'relationship' => 'member_of_site',
			'relationship_guid' => $this->getGUID(),
			'inverse_relationship' => TRUE,
			'type' => 'user',
		);

		$options = array_merge($defaults, $options);

		return elgg_list_entities_from_relationship($options);
	}

	/**
	 * Adds a user to the site.
	 *
	 * @param int $user_guid GUID
	 *
	 * @return bool
	 */
	public function addUser($user_guid) {
		return add_site_user($this->getGUID(), $user_guid);
	}

	/**
	 * Removes a user from the site.
	 *
	 * @param int $user_guid GUID
	 *
	 * @return bool
	 */
	public function removeUser($user_guid) {
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
	 */
	public function getObjects($subtype = "", $limit = 10, $offset = 0) {
		return get_site_objects($this->getGUID(), $subtype, $limit, $offset);
	}

	/**
	 * Adds an object to the site.
	 *
	 * @param int $object_guid GUID
	 *
	 * @return bool
	 */
	public function addObject($object_guid) {
		return add_site_object($this->getGUID(), $object_guid);
	}

	/**
	 * Remvoes an object from the site.
	 *
	 * @param int $object_guid GUID
	 *
	 * @return bool
	 */
	public function removeObject($object_guid) {
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

	/*
	 * EXPORTABLE INTERFACE
	 */

	/**
	 * Return an array of fields which can be exported.
	 *
	 * @return array
	 */
	public function getExportableValues() {
		return array_merge(parent::getExportableValues(), array(
			'name',
			'description',
			'url',
		));
	}

	/**
	 * Halts bootup and redirects to the site front page
	 * if site is in walled garden mode, no user is logged in,
	 * and the URL is not a public page.
	 *
	 * @link http://docs.elgg.org/Tutorials/WalledGarden
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
					'user',
					'_elgg_walled_garden_remove_public_access');

			if (!elgg_is_logged_in()) {
				// hook into the index system call at the highest priority
				elgg_register_plugin_hook_handler('index', 'system', 'elgg_walled_garden_index', 1);

				if (!$this->isPublicPage()) {
					if (!elgg_is_xhr()) {
						$_SESSION['last_forward_from'] = current_page_url();
					}
					register_error(elgg_echo('loggedinrequired'));
					forward();
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
			return TRUE;
		}

		// default public pages
		$defaults = array(
			'walled_garden/.*',
			'login',
			'action/login',
			'register',
			'action/register',
			'forgotpassword',
			'resetpassword',
			'action/user/requestnewpassword',
			'action/user/passwordreset',
			'action/security/refreshtoken',
			'ajax/view/js/languages',
			'upgrade\.php',
			'xml-rpc\.php',
			'mt/mt-xmlrpc\.cgi',
			'css/.*',
			'js/.*',
			'cache/css/.*',
			'cache/js/.*',
			'cron/.*',
			'services/.*',
		);

		// include a hook for plugin authors to include public pages
		$plugins = elgg_trigger_plugin_hook('public_pages', 'walled_garden', NULL, array());

		// allow public pages
		foreach (array_merge($defaults, $plugins) as $public) {
			$pattern = "`^{$CONFIG->url}$public/*$`i";
			if (preg_match($pattern, $url)) {
				return TRUE;
			}
		}

		// non-public page
		return FALSE;
	}
}
