<?php

namespace Elgg\Database\Seeds;

/**
 * Provides seedable methods for database seeding and unit tests
 */
interface Seedable {

	/**
	 * Create a new fake user
	 *
	 * @param array $properties Entity attributes/metadata
	 *
	 * @return \ElggUser
	 */
	public function createUser(array $properties = []);

	/**
	 * Create a new fake group
	 *
	 * @param array $properties Entity attributes/metadata
	 *
	 * @return \ElggGroup
	 */
	public function createGroup(array $properties = []);

	/**
	 * Create a new fake object
	 *
	 * @param array $properties Entity attributes/metadata
	 *
	 * @return \ElggObject
	 */
	public function createObject(array $properties = []);

	/**
	 * Create a new fake site
	 *
	 * @param array $properties Entity attributes/metadata
	 *
	 * @return \ElggSite
	 */
	public function createSite(array $properties = []);
}
