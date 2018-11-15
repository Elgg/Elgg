<?php

namespace Elgg\Database\Seeds;

use Elgg\Collections\Collection;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\QueryBuilder;
use Elgg\Groups\Tool;
use ElggEntity;
use ElggGroup;
use ElggObject;
use ElggUser;
use Exception;
use Faker\Factory;
use Psr\Log\LogLevel;
use Elgg\Database\Seeds\Providers\LocalImage;

/**
 * Seeding trait
 * Can be used to easily create new random users, groups and objects in the database
 *
 * @access private
 */
trait Seeding {

	/**
	 * @var int Max number of items to be created by the seed
	 */
	protected $limit = 20;

	/**
	 * @var \Faker\Generator
	 */
	protected $faker;

	/**
	 * Returns an instance of faker
	 *
	 * @param string $locale Locale
	 *
	 * @return \Faker\Generator
	 */
	public function faker($locale = 'en_US') {
		if (!isset($this->faker)) {
			$this->faker = Factory::create($locale);
		}
		
		$this->faker->addProvider(new LocalImage($this->faker));

		return $this->faker;
	}

	/**
	 * Get site domain
	 * @return string
	 */
	public function getDomain() {
		return elgg_get_site_entity()->getDomain();
	}

	/**
	 * Get valid domain for emails
	 * @return string
	 */
	public function getEmailDomain() {
		$email = elgg_get_site_entity()->email;
		if (!$email) {
			$email = "noreply@{$this->getDomain()}";
		}

		list(, $domain) = explode('@', $email);

		if (sizeof(explode('.', $domain)) <= 1) {
			$domain = 'example.net';
		}

		return $domain;
	}

	/**
	 * Returns random unique subtype
	 * @return bool|string
	 */
	public function getRandomSubtype() {
		return substr(sha1(microtime() . rand()), 0, 25);
	}

	/**
	 * Create a new fake user
	 *
	 * @param array $attributes User entity attributes
	 * @param array $metadata   User entity metadata
	 * @param array $options    Seeding options
	 *
	 * @return ElggUser
	 */
	public function createUser(array $attributes = [], array $metadata = [], array $options = []) {

		$create = function () use ($attributes, $metadata, $options) {
			$metadata['__faker'] = true;

			if (empty($metadata['password'])) {
				$metadata['password'] = generate_random_cleartext_password();
			}

			if (empty($metadata['name'])) {
				$metadata['name'] = $this->faker()->name;
			}

			if (empty($metadata['username'])) {
				$metadata['username'] = $this->getRandomUsername($metadata['name']);
			}

			if (empty($metadata['email'])) {
				$metadata['email'] = $this->getRandomEmail($metadata['username']);
			}

			if (empty($attributes['subtype'])) {
				$attributes['subtype'] = 'user';
			}

			$user = false;

			try {
				$guid = register_user($metadata['username'], $metadata['password'], $metadata['name'], $metadata['email'], false, $attributes['subtype']);

				$user = get_user($guid);
				if (!$user) {
					throw new Exception("Unable to create new user with attributes: " . print_r($attributes, true));
				}

				if (isset($metadata['admin'])) {
					if ($metadata['admin']) {
						$user->makeAdmin();
					} else {
						$user->removeAdmin();
					}
				}

				if (isset($metadata['banned'])) {
					if ($metadata['banned']) {
						$user->ban('Banned by seeder');
					} else {
						$user->unban();
					}
				}

				unset($metadata['username']);
				unset($metadata['password']);
				unset($metadata['name']);
				unset($metadata['email']);
				unset($metadata['banned']);
				unset($metadata['admin']);

				$user->setValidationStatus($this->faker()->boolean(), 'seeder');

				$user->setNotificationSetting('email', false);
				$user->setNotificationSetting('site', true);

				$profile_fields = elgg_extract('profile_fields', $options, []);
				$user = $this->populateMetadata($user, $profile_fields, $metadata);

				$user->save();

				$this->log("Created new user {$user->getDisplayName()} [guid: {$user->guid}]");

				return $user;
			} catch (\RegistrationException $e) {
				if ($user && $user->guid) {
					$user->delete();
				}

				$attr_log = print_r($attributes, true);
				$this->log("User creation failed with message {$e->getMessage()} [attributes: $attr_log]");

				return false;
			}
		};

		$ia = _elgg_services()->session->setIgnoreAccess(true);

		$user = false;
		while (!$user instanceof \ElggUser) {
			try {
				$user = $create();
			} catch (Exception $ex) {
				// try again
			}
		}

		_elgg_services()->session->setIgnoreAccess($ia);

		return $user;

	}

	/**
	 * Create a new fake group
	 *
	 * @param array $attributes Group entity attributes
	 * @param array $metadata   Group entity metadata
	 * @param array $options    Additional options
	 *
	 * @return ElggGroup
	 */
	public function createGroup(array $attributes = [], array $metadata = [], array $options = []) {

		$create = function () use ($attributes, $metadata, $options) {

			$properties = array_merge($metadata, $attributes);

			$properties['__faker'] = true;

			if (!isset($properties['access_id'])) {
				$properties['access_id'] = ACCESS_PUBLIC;
			}

			if (!isset($properties['content_access_mode'])) {
				$properties['content_access_mode'] = ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED;
			}

			if (!isset($properties['membership'])) {
				$properties['membership'] = ACCESS_PUBLIC;
			}

			if (empty($properties['name'])) {
				$properties['name'] = $this->faker()->sentence();
			}

			if (empty($properties['description'])) {
				$properties['description'] = $this->faker()->text($this->faker()->numberBetween(500, 1000));
			}

			if (!isset($properties['owner_guid'])) {
				$user = _elgg_services()->session->getLoggedInUser();
				if (!$user) {
					$user = $this->createUser();
				}

				$properties['owner_guid'] = $user->guid;
			}

			if (!isset($properties['container_guid'])) {
				$properties['container_guid'] = $properties['owner_guid'];
			}

			if (empty($properties['subtype'])) {
				$properties['subtype'] = 'group';
			}

			$owner = get_entity($properties['owner_guid']);
			if (!$owner) {
				return false;
			}

			$container = get_entity($properties['container_guid']);
			if (!$container) {
				return false;
			}

			$tool_options = elgg_extract('group_tools_options', $options, []);
			/* @var $tool_options Collection|Tool[] */

			foreach ($tool_options as $group_option) {
				$prop_name = $group_option->mapMetadataName();
				$prop_value = $group_option->mapMetadataValue();
				$properties[$prop_name] = $prop_value;
			}

			if ($this->faker()->boolean(20)) {
				$properties['featured_group'] = 'yes';
			}

			$group = new ElggGroup();
			foreach ($properties as $name => $value) {
				$group->$name = $value;
			}

			$profile_fields = elgg_extract('profile_fields', $options, []);
			$group = $this->populateMetadata($group, $profile_fields, $properties);

			$group->save();

			if ($group->access_id == ACCESS_PRIVATE) {
				$acls = $group->getOwnedAccessCollections(['subtype' => 'group_acl']);
				if ($acls) {
					$group->access_id = $acls[0]->id;
					$group->save();
				}
			}

			$group->join(get_entity($properties['owner_guid']));

			elgg_create_river_item([
				'view' => 'river/group/create',
				'action_type' => 'create',
				'subject_guid' => $owner->guid,
				'object_guid' => $group->guid,
				'target_guid' => $container->guid,
			]);

			$this->log("Created new group {$group->getDisplayName()} [guid: {$group->guid}]");

			return $group;
		};

		$ia = _elgg_services()->session->setIgnoreAccess(true);

		$group = false;
		while (!$group instanceof \ElggGroup) {
			$group = $create();
		}

		_elgg_services()->session->setIgnoreAccess($ia);

		return $group;
	}

	/**
	 * Create a new fake object
	 *
	 * @param array $attributes Object entity attributes
	 * @param array $metadata   Object entity metadata
	 * @param array $options    Additional options
	 *
	 * @return ElggObject
	 */
	public function createObject(array $attributes = [], array $metadata = [], array $options = []) {

		$create = function () use ($attributes, $metadata, $options) {

			$properties = array_merge($metadata, $attributes);

			$properties['__faker'] = true;

			if (empty($properties['title'])) {
				$properties['title'] = $this->faker()->sentence();
			}

			if (empty($properties['description'])) {
				$properties['description'] = $this->faker()->text($this->faker()->numberBetween(500, 1000));
			}

			if (empty($properties['subtype'])) {
				$properties['subtype'] = $this->getRandomSubtype();
			}

			if (empty($properties['tags'])) {
				$properties['tags'] = $this->faker()->words(10);
			}

			if (!isset($properties['container_guid'])) {
				if (isset($properties['owner_guid'])) {
					$properties['container_guid'] = $properties['owner_guid'];
				} else {
					$container = _elgg_services()->session->getLoggedInUser();
					if (!$container) {
						$container = $this->createUser();
					}

					$properties['container_guid'] = $container->guid;
				}
			}

			$container = get_entity($properties['container_guid']);
			if (!$container) {
				return false;
			}

			if (!isset($properties['owner_guid'])) {
				$owner = $container;
				$properties['owner_guid'] = $owner->guid;
			}

			$owner = get_entity($properties['owner_guid']);
			if (!$owner) {
				return false;
			}

			if (!isset($properties['access_id'])) {
				$properties['access_id'] = ACCESS_PUBLIC;
			}

			$class = elgg_get_entity_class('object', $properties['subtype']);
			if ($class && class_exists($class)) {
				$object = new $class();
			} else {
				$object = new ElggObject();
			}

			foreach ($properties as $name => $value) {
				$object->$name = $value;
			}

			$profile_fields = elgg_extract('profile_fields', $options, []);
			$object = $this->populateMetadata($object, $profile_fields, $properties);

			if (elgg_extract('save', $options, true)) {
				$object->save();
			}

			$type_str = elgg_echo("item:object:{$object->getSubtype()}");

			$this->log("Created new item in {$type_str} {$object->getDisplayName()} [guid: {$object->guid}]");

			return $object;
		};

		$ia = _elgg_services()->session->setIgnoreAccess(true);

		$object = false;
		while (!$object instanceof \ElggObject) {
			$object = $create();
		}

		_elgg_services()->session->setIgnoreAccess($ia);

		return $object;

	}

	/**
	 * Create a new fake site
	 *
	 * @param array $attributes Object entity attributes
	 * @param array $metadata   Object entity metadata
	 *
	 * @return ElggObject
	 */
	public function createSite(array $attributes = [], array $metadata = []) {
		// We don't want to create more than one site
		return elgg_get_site_entity();
	}

	/**
	 * Returns random fake user
	 *
	 * @param int[] $exclude GUIDs to exclude
	 *
	 * @return ElggUser|false
	 */
	public function getRandomUser(array $exclude = []) {

		$exclude[] = 0;

		$users = elgg_get_entities([
			'types' => 'user',
			'metadata_names' => ['__faker'],
			'limit' => 1,
			'wheres' => [
				function(QueryBuilder $qb) use ($exclude) {
					return $qb->compare('e.guid', 'NOT IN', $exclude, ELGG_VALUE_INTEGER);
				}
			],
			'order_by' => new OrderByClause('RAND()', null),
		]);

		return $users ? $users[0] : false;
	}

	/**
	 * Returns random fake group
	 *
	 * @param int[] $exclude GUIDs to exclude
	 *
	 * @return ElggGroup|false
	 */
	public function getRandomGroup(array $exclude = []) {

		$exclude[] = 0;

		$groups = elgg_get_entities([
			'types' => 'group',
			'metadata_names' => ['__faker'],
			'limit' => 1,
			'wheres' => [
				function(QueryBuilder $qb) use ($exclude) {
					return $qb->compare('e.guid', 'NOT IN', $exclude, ELGG_VALUE_INTEGER);
				}
			],
			'order_by' => new OrderByClause('RAND()', null),
		]);

		return $groups ? $groups[0] : false;
	}

	/**
	 * Get random access id
	 *
	 * @param ElggUser   $user      User
	 * @param ElggEntity $container Container
	 *
	 * @return int
	 */
	public function getRandomAccessId(\ElggUser $user = null, ElggEntity $container = null) {

		$params = [
			'container_guid' => $container ? $container->guid : null,
		];

		$access_array = get_write_access_array($user->guid, null, null, $params);

		$access_key = array_rand($access_array, 1);

		return $access_array[$access_key];
	}

	/**
	 * Generates a unique available and valid username
	 *
	 * @param string $name Display name or other prefix to use as basis
	 *
	 * @return string
	 */
	public function getRandomUsername($name = null) {

		$make = function($name = null)  {
			if (!$name) {
				return strtolower($this->faker()->firstName . '.' . $this->faker()->lastName);
			}

			return implode('.', preg_split('/\W/', $name));
		};

		$validate = function($username) {
			try {
				elgg()->accounts->assertValidUsername($username, true);
				return true;
			} catch (\RegistrationException $e) {
				return false;
			}
		};

		$username = $make($name);
		while (!$validate($username)) {
			$username = $make();
		}

		return $username;
	}

	/**
	 * Generate a random valid email
	 *
	 * @param string $base Email username part
	 * @return string
	 */
	public function getRandomEmail($base = null) {

		$make = function($base = null) {
			$base = $this->getRandomUsername($base);
			return $base . '@' . $this->getEmailDomain();
		};

		$validate = function($email) {
			try {
				elgg()->accounts->assertValidEmail($email, true);
				return true;
			} catch (\RegistrationException $e) {
				return false;
			}
		};

		$email = $make($base);
		while (!$validate($email)) {
			$email = $make();
		}

		return $email;
	}

	/**
	 * Set random metadata
	 *
	 * @param ElggEntity $entity   Entity
	 * @param array      $fields   An array of profile fields in $name => $input_type format
	 * @param array      $metadata Other metadata $name => $value pairs to set
	 *
	 * @return ElggEntity
	 */
	public function populateMetadata(ElggEntity $entity, array $fields = [], array $metadata = []) {

		foreach ($fields as $name => $type) {
			if (isset($metadata[$name])) {
				continue;
			}

			switch ($name) {
				case 'phone' :
				case 'mobile' :
					$metadata[$name] = $this->faker()->phoneNumber;
					break;

				default :
					switch ($type) {
						case 'plaintext' :
						case 'longtext' :
							$metadata[$name] = $this->faker()->text($this->faker()->numberBetween(500, 1000));
							break;

						case 'text' :
							$metadata[$name] = $this->faker()->sentence;
							break;

						case 'tags' :
							$metadata[$name] = $this->faker()->words(10);
							break;

						case 'url' :
							$metadata[$name] = $this->faker()->url;
							break;

						case 'email' :
							$metadata[$name] = $this->faker()->email;
							break;

						case 'number' :
							$metadata[$name] = $this->faker()->randomNumber();
							break;

						case 'date' :
							$metadata[$name] = $this->faker()->unixTime;
							break;

						case 'password' :
							$metadata[$name] = generate_random_cleartext_password();
							break;

						case 'location' :
							$metadata[$name] = $this->faker()->address;
							$metadata['geo:lat'] = $this->faker()->latitude;
							$metadata['geo:long'] = $this->faker()->longitude;
							break;

						default :
							$metadata[$name] = '';
							break;
					}

					break;
			}
		}

		foreach ($metadata as $key => $value) {
			if (array_key_exists($key, $fields) && $entity instanceof ElggUser) {
				$entity->deleteAnnotations("profile:$key");
				$value = (array) $value;
				foreach ($value as $val) {
					$entity->annotate("profile:$key", $val, $this->getRandomAccessId($entity), $entity->guid);
				}
			} else {
				$entity->$key = $value;
			}
		}

		return $entity;
	}

	/**
	 * Create an icon for an entity
	 *
	 * @param ElggEntity $entity Entity
	 *
	 * @return bool
	 */
	public function createIcon(ElggEntity $entity) {

		$icon_location = $this->faker()->image();
		if (empty($icon_location)) {
			return false;
		}

		$result = $entity->saveIconFromLocalFile($icon_location);

		if ($result && $entity instanceof ElggUser) {
			elgg_create_river_item([
				'view' => 'river/user/default/profileiconupdate',
				'action_type' => 'update',
				'subject_guid' => $entity->guid,
				'object_guid' => $entity->guid,
			]);
		}

		return $result;
	}

	/**
	 * Create comments/replies
	 *
	 * @param ElggEntity $entity Entity to comment on
	 * @param int        $limit  Number of comments to create
	 *
	 * @return int Number of generated comments
	 */
	public function createComments(ElggEntity $entity, $limit = null) {

		$ia = _elgg_services()->session->setIgnoreAccess(true);

		$tries = 0;
		$success = 0;

		if (!$limit) {
			$limit = $this->faker()->numberBetween(1, 20);
		}

		while ($tries < $limit) {
			$comment = new \ElggComment();
			$comment->owner_guid = $this->getRandomUser()->guid ? : $entity->owner_guid;
			$comment->container_guid = $entity->guid;
			$comment->description = $this->faker()->paragraph;

			$tries++;
			if ($comment->save()) {
				$success++;
			}
		}

		_elgg_services()->session->setIgnoreAccess($ia);

		return $success;

	}

	/**
	 * Create likes
	 *
	 * @param ElggEntity $entity Entity to like
	 * @param int        $limit  Number of likes to create
	 *
	 * @return int
	 */
	public function createLikes(ElggEntity $entity, $limit = null) {

		$ia = _elgg_services()->session->setIgnoreAccess(true);

		$success = 0;

		if (!$limit) {
			$limit = $this->faker()->numberBetween(1, 20);
		}

		while ($success < $limit) {
			if ($entity->annotate('likes', true, $entity->access_id, $this->getRandomUser()->guid)) {
				$success++;
			}
		}

		_elgg_services()->session->setIgnoreAccess($ia);

		return $success;
	}

	/**
	 * Log a message
	 *
	 * @param string $msg   Message to log
	 * @param string $level Message level
	 *                      Note that 'ERROR' will terminate further code execution
	 *
	 * @return void
	 */
	public function log($msg, $level = LogLevel::NOTICE) {
		elgg_log($msg, $level);
	}

}