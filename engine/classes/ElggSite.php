<?php

use Elgg\Database\EntityTable;
use Elgg\Database\Select;
use Elgg\Exceptions\SecurityException;

/**
 * A Site entity.
 *
 * \ElggSite represents a single site entity.
 *
 * An \ElggSite object is an \ElggEntity child class with the subtype of "site."
 * It is created upon installation and holds information about a site:
 *  - name
 *  - description
 *  - url
 *
 * Every \ElggEntity belongs to a site.
 *
 * @note Internal: \ElggSite represents a single row from the entities table.
 *
 * @link       http://learn.elgg.org/en/stable/design/database.html
 *
 * @property      string $name        The name or title of the website
 * @property      string $description A motto, mission statement, or description of the website
 * @property-read string $url         The root web address for the site, including trailing slash
 */
class ElggSite extends \ElggEntity {

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['type'] = 'site';
		$this->attributes['subtype'] = 'site';

		$this->attributes['owner_guid'] = 0;
		$this->attributes['container_guid'] = 0;

		$this->attributes['access_id'] = ACCESS_PUBLIC;
	}

	/**
	 * {@inheritdoc}
	 */
	public function save(): bool {
		$qb = Select::fromTable(EntityTable::TABLE_NAME);
		$qb->select('*')
			->where($qb->compare('type', '=', 'site', ELGG_VALUE_STRING));

		$row = $this->getDatabase()->getDataRow($qb);
		if (!empty($row)) {
			if ($row->guid == $this->attributes['guid']) {
				// can save active site
				return parent::save();
			}

			_elgg_services()->logger->error('More than 1 site entity cannot be created.');
			return false;
		}

		return parent::save();
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete(bool $recursive = true, bool $persistent = null): bool {
		if ($this->guid === 1) {
			throw new SecurityException('You cannot delete the current site');
		}
		
		return parent::delete($recursive, $persistent);
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
	public function disable(string $reason = '', bool $recursive = true): bool {
		if ($this->guid == 1) {
			throw new SecurityException('You cannot disable the current site');
		}

		return parent::disable($reason, $recursive);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __set($name, $value) {
		if ($name === 'url') {
			_elgg_services()->logger->warning('ElggSite::url cannot be set');
			return;
		}
		
		parent::__set($name, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __get($name) {
		if ($name === 'url') {
			return $this->getURL();
		}
		
		return parent::__get($name);
	}

	/**
	 * Returns the URL for this site
	 *
	 * @return string The URL
	 */
	public function getURL(): string {
		return _elgg_services()->config->wwwroot;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isCacheable(): bool {
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function prepareObject(\Elgg\Export\Entity $object) {
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
	public function getDomain(): string {
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
	public function getEmailAddress(): string {
		$email = $this->email;
		if (empty($email)) {
			// If all else fails, use the domain of the site.
			$token = _elgg_services()->crypto->getRandomString(24);
			$email = "noreply-{$token}@{$this->getDomain()}";
		}

		return $email;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function updateLastAction(int $posted = null): int {
		// setting last action on ElggSite makes no sense... just returning current value to be compliant
		return $this->last_action;
	}
}
