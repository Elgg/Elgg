<?php
/**
 *
 */

namespace Elgg\Database\Seeds;

/**
 * Provides seedable methods for database seeding and unit tests
 */
interface Seedable {

	/**
	 * Create a new fake user
	 *
	 * @param array $attributes User entity attributes
	 * @param array $metadata   User entity metadata
	 *
	 * @return \ElggUser
	 */
	public function createUser(array $attributes = [], array $metadata = []);

	/**
	 * Create a new fake group
	 *
	 * @param array $attributes Group entity attributes
	 * @param array $metadata   Group entity metadata
	 *
	 * @return \ElggGroup
	 */
	public function createGroup(array $attributes = [], array $metadata = []);

	/**
	 * Create a new fake object
	 *
	 * @param array $attributes Object entity attributes
	 * @param array $metadata   Object entity metadata
	 *
	 * @return \ElggObject
	 */
	public function createObject(array $attributes = [], array $metadata = []);

	/**
	 * Create a new fake site
	 *
	 * @param array $attributes Site entity attributes
	 * @param array $metadata   Site entity metadata
	 *
	 * @return \ElggSite
	 */
	public function createSite(array $attributes = [], array $metadata = []);
}