<?php
/**
 * ElggSite
 * Representation of a "site" in the system.
 * @author Curverider Ltd <info@elgg.com>
 * @package Elgg
 * @subpackage Core
 */
class ElggSite extends ElggEntity {
	/**
	 * Initialise the attributes array.
	 * This is vital to distinguish between metadata and base parameters.
	 *
	 * Place your base parameters here.
	 */
	protected function initialise_attributes() {
		parent::initialise_attributes();

		$this->attributes['type'] = "site";
		$this->attributes['name'] = "";
		$this->attributes['description'] = "";
		$this->attributes['url'] = "";
		$this->attributes['tables_split'] = 2;
	}

	/**
	 * Construct a new site object, optionally from a given id value.
	 *
	 * @param mixed $guid If an int, load that GUID.
	 * 	If a db row then will attempt to load the rest of the data.
	 * @throws Exception if there was a problem creating the site.
	 */
	function __construct($guid = null) {
		$this->initialise_attributes();

		if (!empty($guid)) {
			// Is $guid is a DB row - either a entity row, or a site table row.
			if ($guid instanceof stdClass) {
				// Load the rest
				if (!$this->load($guid->guid)) {
					throw new IOException(sprintf(elgg_echo('IOException:FailedToLoadGUID'), get_class(), $guid->guid));
				}
			}

			// Is $guid is an ElggSite? Use a copy constructor
			else if ($guid instanceof ElggSite) {
				elgg_deprecated_notice('This type of usage of the ElggSite constructor was deprecated. Please use the clone method.', 1.7);
				
				foreach ($guid->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}
			}

			// Is this is an ElggEntity but not an ElggSite = ERROR!
			else if ($guid instanceof ElggEntity) {
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonElggSite'));
			}

			// See if this is a URL
			else if (strpos($guid, "http") !== false) {
				$guid = get_site_by_url($guid);
				foreach ($guid->attributes as $key => $value) {
					$this->attributes[$key] = $value;
				}
			}

			// We assume if we have got this far, $guid is an int
			else if (is_numeric($guid)) {
				if (!$this->load($guid)) {
					throw new IOException(sprintf(elgg_echo('IOException:FailedToLoadGUID'), get_class(), $guid));
				}
			}

			else {
				throw new InvalidParameterException(elgg_echo('InvalidParameterException:UnrecognisedValue'));
			}
		}
	}

	/**
	 * Override the load function.
	 * This function will ensure that all data is loaded (were possible), so
	 * if only part of the ElggSite is loaded, it'll load the rest.
	 *
	 * @param int $guid
	 */
	protected function load($guid) {
		// Test to see if we have the generic stuff
		if (!parent::load($guid)) {
			return false;
		}

		// Check the type
		if ($this->attributes['type']!='site') {
			throw new InvalidClassException(sprintf(elgg_echo('InvalidClassException:NotValidElggStar'), $guid, get_class()));
		}

		// Load missing data
		$row = get_site_entity_as_row($guid);
		if (($row) && (!$this->isFullyLoaded())) {
			// If $row isn't a cached copy then increment the counter
			$this->attributes['tables_loaded'] ++;
		}

		// Now put these into the attributes array as core values
		$objarray = (array) $row;
		foreach($objarray as $key => $value) {
			$this->attributes[$key] = $value;
		}

		return true;
	}

	/**
	 * Override the save function.
	 */
	public function save() {
		// Save generic stuff
		if (!parent::save()) {
			return false;
		}

		// Now save specific stuff
		return create_site_entity($this->get('guid'), $this->get('name'), $this->get('description'), $this->get('url'));
	}

	/**
	 * Delete this site.
	 */
	public function delete() {
		global $CONFIG;
		if ($CONFIG->site->getGUID() == $this->guid) {
			throw new SecurityException('SecurityException:deletedisablecurrentsite');
		}

		return parent::delete();
	}

	/**
	 * Disable override to add safety rail.
	 *
	 * @param unknown_type $reason
	 */
	public function disable($reason = "") {
		global $CONFIG;

		if ($CONFIG->site->getGUID() == $this->guid) {
			throw new SecurityException('SecurityException:deletedisablecurrentsite');
		}

		return parent::disable($reason);
	}

	/**
	 * Return a list of users using this site.
	 *
	 * @param int $limit
	 * @param int $offset
	 * @return array of ElggUsers
	 */
	public function getMembers($limit = 10, $offset = 0) {
		get_site_members($this->getGUID(), $limit, $offset);
	}

	/**
	 * Add a user to the site.
	 *
	 * @param int $user_guid
	 */
	public function addUser($user_guid) {
		return add_site_user($this->getGUID(), $user_guid);
	}

	/**
	 * Remove a site user.
	 *
	 * @param int $user_guid
	 */
	public function removeUser($user_guid) {
		return remove_site_user($this->getGUID(), $user_guid);
	}

	/**
	 * Get an array of member ElggObjects.
	 *
	 * @param string $subtype
	 * @param int $limit
	 * @param int $offset
	 */
	public function getObjects($subtype="", $limit = 10, $offset = 0) {
		get_site_objects($this->getGUID(), $subtype, $limit, $offset);
	}

	/**
	 * Add an object to the site.
	 *
	 * @param int $user_id
	 */
	public function addObject($object_guid) {
		return add_site_object($this->getGUID(), $object_guid);
	}

	/**
	 * Remove a site user.
	 *
	 * @param int $user_id
	 */
	public function removeObject($object_guid) {
		return remove_site_object($this->getGUID(), $object_guid);
	}

	/**
	 * Get the collections associated with a site.
	 *
	 * @param string $type
	 * @param int $limit
	 * @param int $offset
	 * @return unknown
	 */
	public function getCollections($subtype="", $limit = 10, $offset = 0) {
		get_site_collections($this->getGUID(), $subtype, $limit, $offset);
	}

	// EXPORTABLE INTERFACE ////////////////////////////////////////////////////////////

	/**
	 * Return an array of fields which can be exported.
	 */
	public function getExportableValues() {
		return array_merge(parent::getExportableValues(), array(
			'name',
			'description',
			'url',
		));
	}
	
	public function check_walled_garden() {
		global $CONFIG;
		
		if ($CONFIG->walled_garden && !isloggedin()) {
			// hook into the index system call at the highest priority
			register_plugin_hook('index', 'system', 'elgg_walled_garden_index', 1);
			
			if (!$this->is_public_page()) {
				register_error(elgg_echo('loggedinrequired'));
				forward();
			}
		}
	}
	
	public function is_public_page($url='') {
		global $CONFIG;
		
		if (empty($url)) {
			$url = current_page_url();
			
			// do not check against URL queries
			if ($pos = strpos($url, '?')) {
				$url = substr($url, 0, $pos);
			}
		}
		
		// always allow index page
		if ($url == $CONFIG->url) {
			return TRUE;
		}
		
		// default public pages
		$defaults = array(
			'action/login',
			'pg/register',
			'action/register',
			'account/forgotten_password\.php',
			'action/user/requestnewpassword',
			'pg/resetpassword',
			'upgrade\.php',
			'xml-rpc\.php',
			'mt/mt-xmlrpc\.cgi',
			'_css/css\.css',
			'_css/js\.php',
		);
		
		// include a hook for plugin authors to include public pages
		$plugins = trigger_plugin_hook('public_pages', 'walled_garden', NULL, array());
		
		// lookup admin-specific public pages
		
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
