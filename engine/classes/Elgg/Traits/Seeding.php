<?php

namespace Elgg\Traits;

use Elgg\Collections\Collection;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Seeds\Providers\LocalImage;
use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Exceptions\Exception;
use Elgg\Exceptions\Seeding\MaxAttemptsException;
use Elgg\Groups\Tool;
use Elgg\Traits\Seeding\GroupHelpers;
use Elgg\Traits\Seeding\TimeHelpers;
use Faker\Factory;
use Psr\Log\LogLevel;

/**
 * Seeding trait
 * Can be used to easily create new random users, groups and objects in the database
 *
 * @internal
 */
trait Seeding {

	use GroupHelpers;
	use TimeHelpers;
	
	/**
	 * This can't be a const because of PHP trait limitation
	 *
	 * @var int
	 */
	protected $MAX_ATTEMPTS = 10;
	
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
	public function faker(string $locale = 'en_US'): \Faker\Generator {
		if (!isset($this->faker)) {
			$this->faker = Factory::create($locale);
		}
		
		$this->faker->addProvider(new LocalImage($this->faker));

		return $this->faker;
	}

	/**
	 * Get site domain
	 *
	 * @return string
	 */
	public function getDomain(): string {
		return elgg_get_site_entity()->getDomain();
	}

	/**
	 * Get valid domain for emails
	 *
	 * @return string
	 */
	public function getEmailDomain(): string {
		$email = elgg_get_site_entity()->email;
		if (!$email) {
			$email = "noreply@{$this->getDomain()}";
		}

		list(, $domain) = explode('@', $email);

		if (count(explode('.', $domain)) <= 1) {
			$domain = 'example.net';
		}

		return $domain;
	}

	/**
	 * Returns random unique subtype
	 *
	 * @return bool|string
	 */
	public function getRandomSubtype(): bool|string {
		return substr(sha1(microtime() . rand()), 0, 25);
	}

	/**
	 * Create a new fake user
	 *
	 * @param array $properties Entity attributes/metadata
	 * @param array $options    Seeding options
	 *
	 * @return \ElggUser
	 * @throws Exception
	 * @throws MaxAttemptsException
	 */
	public function createUser(array $properties = [], array $options = []): \ElggUser {

		$create = function () use ($properties, $options) {
			$properties['__faker'] = true;

			if (empty($properties['password'])) {
				$properties['password'] = elgg_generate_password();
			}

			if (empty($properties['name'])) {
				$properties['name'] = $this->faker()->name;
			}

			if (empty($properties['username'])) {
				$properties['username'] = $this->getRandomUsername($properties['name']);
			}

			if (empty($properties['email'])) {
				$properties['email'] = $this->getRandomEmail($properties['username']);
			}

			if (empty($properties['subtype'])) {
				$properties['subtype'] = 'user';
			}

			$user = false;

			try {
				$user = elgg_register_user([
					'username' => elgg_extract('username', $properties),
					'password' => elgg_extract('password', $properties),
					'name' => elgg_extract('name', $properties),
					'email' => elgg_extract('email', $properties),
					'subtype' => elgg_extract('subtype', $properties),
				]);
				
				// make sure we have a cleanly loaded user entity
				$user = get_user($user->guid);

				if (!isset($properties['time_created'])) {
					$properties['time_created'] = $this->getRandomCreationTimestamp();
				}
				
				if (!empty($properties['time_created'])) {
					$user->time_created = $properties['time_created'];
				}
				
				if (isset($properties['admin'])) {
					if ($properties['admin']) {
						$user->makeAdmin();
					} else {
						$user->removeAdmin();
					}
				}

				if (isset($properties['banned'])) {
					if ($properties['banned']) {
						$user->ban('Banned by seeder');
					} else {
						$user->unban();
					}
				}

				if (!isset($properties['validated'])) {
					$properties['validated'] = $this->faker()->boolean(80);
				}
				
				$user->setValidationStatus((bool) $properties['validated'], 'seeder');
				
				if (!$user->isValidated()) {
					$user->disable('seeder invalidation');
				}
				
				unset($properties['username']);
				unset($properties['password']);
				unset($properties['name']);
				unset($properties['email']);
				unset($properties['banned']);
				unset($properties['admin']);
				unset($properties['validated']);
				
				$user->setNotificationSetting('email', false);
				$user->setNotificationSetting('site', true);

				$profile_fields = elgg_extract('profile_fields', $options, []);
				/* @var $user \ElggUser */
				$user = $this->populateMetadata($user, $profile_fields, $properties);

				$user->save();

				$this->log("Created new user {$user->getDisplayName()} [guid: {$user->guid}]");

				return $user;
			} catch (RegistrationException $e) {
				if ($user && $user->guid) {
					$user->delete();
				}

				$attr_log = print_r($properties, true);
				$this->log("User creation failed with message {$e->getMessage()} [properties: $attr_log]");

				return false;
			}
		};

		return elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($create) {
			$user = false;
			$attempts = 0;
			while (!$user instanceof \ElggUser && $attempts < $this->MAX_ATTEMPTS) {
				$attempts++;
				
				try {
					$user = $create();
				} catch (\Exception $ex) {
					// try again
				}
			}
			
			if (!$user instanceof \ElggUser) {
				throw new MaxAttemptsException("Unable to create a user after {$attempts} seeding attempts");
			}
			
			return $user;
		});
	}

	/**
	 * Create a new fake group
	 *
	 * @param array $properties Entity attributes/metadata
	 * @param array $options    Additional options
	 *
	 * @return \ElggGroup
	 * @throws MaxAttemptsException
	 */
	public function createGroup(array $properties = [], array $options = []): \ElggGroup {

		$create = function () use ($properties, $options) {
			$properties['__faker'] = true;

			if (!isset($properties['time_created'])) {
				$properties['time_created'] = $this->getRandomCreationTimestamp();
			}

			if (!isset($properties['access_id'])) {
				$properties['access_id'] = ACCESS_PUBLIC;
			}

			if (!isset($properties['content_access_mode'])) {
				$properties['content_access_mode'] = \ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED;
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
				$user = _elgg_services()->session_manager->getLoggedInUser();
				if (!$user) {
					$user = $this->getRandomUser();
				}
				
				if (!$user) {
					return false;
				}

				$properties['owner_guid'] = $user->guid;
			}
			
			if (!elgg_entity_exists($properties['owner_guid'])) {
				return false;
			}

			if (!isset($properties['container_guid'])) {
				$properties['container_guid'] = $properties['owner_guid'];
			}
			
			if (!elgg_entity_exists($properties['container_guid'])) {
				return false;
			}

			if (empty($properties['subtype'])) {
				$properties['subtype'] = 'group';
			}

			$tool_options = elgg_extract('group_tool_options', $options, []);
			/* @var $tool_options Collection|Tool[] */

			foreach ($tool_options as $group_option) {
				$prop_name = $group_option->mapMetadataName();
				$prop_value = $group_option->mapMetadataValue();
				$properties[$prop_name] = $prop_value;
			}

			if ($this->faker()->boolean(20)) {
				$properties['featured_group'] = 'yes';
			}

			$group = new \ElggGroup();
			foreach ($properties as $name => $value) {
				switch ($name) {
					case 'type':
						break;
					case 'subtype':
						$group->setSubtype($value);
						break;
					default:
						$group->$name = $value;
						break;
				}
			}

			$profile_fields = elgg_extract('profile_fields', $options, []);
			$group = $this->populateMetadata($group, $profile_fields, $properties);

			if (!$group->save()) {
				return false;
			}

			if ($group->access_id === ACCESS_PRIVATE) {
				$acl = $group->getOwnedAccessCollection('group_acl');
				if ($acl instanceof \ElggAccessCollection) {
					$group->access_id = $acl->id;
					$group->save();
				}
			}

			$group->join(get_entity($properties['owner_guid']));

			elgg_create_river_item([
				'view' => 'river/group/create',
				'action_type' => 'create',
				'subject_guid' => $properties['owner_guid'],
				'object_guid' => $group->guid,
				'target_guid' => $properties['container_guid'],
				'posted' => $group->time_created,
			]);

			$this->log("Created new group {$group->getDisplayName()} [guid: {$group->guid}]");

			return $group;
		};

		return elgg_call(ELGG_IGNORE_ACCESS, function() use ($create) {
			$group = false;
			$attempts = 0;
			while (!$group instanceof \ElggGroup && $attempts < $this->MAX_ATTEMPTS) {
				$attempts++;
				
				$group = $create();
			}
			
			if (!$group instanceof \ElggGroup) {
				throw new MaxAttemptsException("Unable to create a group after {$attempts} seeding attempts");
			}
			
			return $group;
		});
	}

	/**
	 * Create a new fake object
	 *
	 * @param array $properties Entity attributes/metadata
	 * @param array $options    Additional options
	 *
	 * @return \ElggObject
	 * @throws MaxAttemptsException
	 */
	public function createObject(array $properties = [], array $options = []): \ElggObject {

		$create = function () use ($properties, $options) {
			$properties['__faker'] = true;

			if (!isset($properties['time_created'])) {
				$properties['time_created'] = $this->getRandomCreationTimestamp();
			}

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

			if (!isset($properties['owner_guid'])) {
				$user = _elgg_services()->session_manager->getLoggedInUser();
				if (!$user) {
					$user = $this->getRandomUser();
				}
				
				if (!$user) {
					return false;
				}
				
				$properties['owner_guid'] = $user->guid;
			}
			
			if (!elgg_entity_exists($properties['owner_guid'])) {
				return false;
			}
			
			if (!isset($properties['container_guid'])) {
				$properties['container_guid'] = $properties['owner_guid'];
			}
			
			if (!elgg_entity_exists($properties['container_guid'])) {
				return false;
			}

			if (!isset($properties['access_id'])) {
				$properties['access_id'] = ACCESS_PUBLIC;
			}

			$class = elgg_get_entity_class('object', $properties['subtype']);
			if ($class && class_exists($class)) {
				$object = new $class();
			} else {
				$object = new \ElggObject();
			}

			foreach ($properties as $name => $value) {
				switch ($name) {
					case 'type':
						break;
					case 'subtype':
						$object->setSubtype($value);
						break;
					default:
						$object->$name = $value;
						break;
				}
			}

			$profile_fields = elgg_extract('profile_fields', $options, []);
			$object = $this->populateMetadata($object, $profile_fields, $properties);

			if (elgg_extract('save', $options, true)) {
				if (!$object->save()) {
					return false;
				}
			}

			$type_str = elgg_echo("item:object:{$object->getSubtype()}");

			$this->log("Created new item in {$type_str} {$object->getDisplayName()} [guid: {$object->guid}]");

			return $object;
		};

		return elgg_call(ELGG_IGNORE_ACCESS, function() use ($create) {
			$object = false;
			$attempts = 0;
			while (!$object instanceof \ElggObject && $attempts < $this->MAX_ATTEMPTS) {
				$attempts++;
				
				$object = $create();
			}
			
			if (!$object instanceof \ElggObject) {
				throw new MaxAttemptsException("Unable to create an object after {$attempts} seeding attempts");
			}
			
			return $object;
		});
	}

	/**
	 * Create a new fake site
	 *
	 * @param array $properties Entity attributes/metadata
	 *
	 * @return \ElggSite
	 */
	public function createSite(array $properties = []): \ElggSite {
		// We don't want to create more than one site
		return elgg_get_site_entity();
	}

	/**
	 * Returns random fake user
	 *
	 * @param int[] $exclude      GUIDs to exclude
	 * @param bool  $allow_create If no existing random user could be found create a new user (default: true)
	 *
	 * @return \ElggUser|false
	 */
	public function getRandomUser(array $exclude = [], bool $allow_create = true) {

		$exclude[] = 0;

		// make sure the random user isn't disabled
		$users = elgg_call(ELGG_HIDE_DISABLED_ENTITIES, function() use ($exclude) {
			return elgg_get_entities([
				'types' => 'user',
				'metadata_names' => ['__faker'],
				'limit' => 1,
				'wheres' => [
					function(QueryBuilder $qb, $main_alias) use ($exclude) {
						return $qb->compare("{$main_alias}.guid", 'NOT IN', $exclude, ELGG_VALUE_INTEGER);
					}
				],
				'order_by' => new OrderByClause('RAND()', null),
			]);
		});

		if (!empty($users)) {
			return $users[0];
		}

		if ($allow_create) {
			$profile_fields_config = _elgg_services()->fields->get('user', 'user');
			$profile_fields = [];
			foreach ($profile_fields_config as $field) {
				$profile_fields[$field['name']] = $field['#type'];
			}
			
			return $this->createUser([
				'validated' => true,
			], [
				'profile_fields' => $profile_fields,
			]);
		}

		return false;
	}

	/**
	 * Returns random fake group
	 *
	 * @param int[] $exclude      GUIDs to exclude
	 * @param bool  $allow_create If no existing random group could be found create a new group (default: true)
	 *
	 * @return \ElggGroup|false
	 */
	public function getRandomGroup(array $exclude = [], bool $allow_create = true) {

		$exclude[] = 0;

		$groups = elgg_get_entities([
			'types' => 'group',
			'metadata_names' => ['__faker'],
			'limit' => 1,
			'wheres' => [
				function(QueryBuilder $qb, $main_alias) use ($exclude) {
					return $qb->compare("{$main_alias}.guid", 'NOT IN', $exclude, ELGG_VALUE_INTEGER);
				}
			],
			'order_by' => new OrderByClause('RAND()', null),
		]);
		
		if (!empty($groups)) {
			return $groups[0];
		}

		if ($allow_create) {
			$profile_fields_config = _elgg_services()->fields->get('group', 'group');
			$profile_fields = [];
			foreach ($profile_fields_config as $field) {
				$profile_fields[$field['name']] = $field['#type'];
			}
		
			return $this->createGroup([
				'access_id' => $this->getRandomGroupVisibility(),
				'content_access_mode' => $this->getRandomGroupContentAccessMode(),
				'membership' => $this->getRandomGroupMembership(),
			], [
				'profile_fields' => $profile_fields,
				'group_tool_options' => _elgg_services()->group_tools->all(),
			]);
		}
		
		return false;
	}

	/**
	 * Get random access id
	 *
	 * @param \ElggUser   $user      User
	 * @param \ElggEntity $container Container
	 *
	 * @return int
	 */
	public function getRandomAccessId(\ElggUser $user = null, \ElggEntity $container = null) {
		$access_array = elgg_get_write_access_array($user->guid, false, [
			'container_guid' => $container?->guid,
		]);

		return array_rand($access_array, 1);
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
				return elgg_strtolower($this->faker()->firstName . '.' . $this->faker()->lastName);
			}

			return implode('.', preg_split('/\W/', $name));
		};

		$validate = function($username) {
			try {
				_elgg_services()->accounts->assertValidUsername($username, true);
				return true;
			} catch (RegistrationException $e) {
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
	 *
	 * @return string
	 */
	public function getRandomEmail($base = null) {

		$make = function($base = null) {
			$base = $this->getRandomUsername($base);
			return $base . '@' . $this->getEmailDomain();
		};

		$validate = function($email) {
			try {
				_elgg_services()->accounts->assertValidEmail($email, true);
				return true;
			} catch (RegistrationException $e) {
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
	 * @param \ElggEntity $entity   Entity
	 * @param array       $fields   An array of profile fields in $name => $input_type format
	 * @param array       $metadata Other metadata $name => $value pairs to set
	 *
	 * @return \ElggEntity
	 */
	public function populateMetadata(\ElggEntity $entity, array $fields = [], array $metadata = []): \ElggEntity {

		foreach ($fields as $name => $type) {
			if (isset($metadata[$name])) {
				continue;
			}

			switch ($name) {
				case 'phone':
				case 'mobile':
					$metadata[$name] = $this->faker()->phoneNumber;
					break;

				default:
					switch ($type) {
						case 'plaintext':
						case 'longtext':
							$metadata[$name] = $this->faker()->text($this->faker()->numberBetween(500, 1000));
							break;

						case 'text':
							$metadata[$name] = $this->faker()->sentence;
							break;

						case 'tags':
							$metadata[$name] = $this->faker()->words(10);
							break;

						case 'url':
							$metadata[$name] = $this->faker()->url;
							break;

						case 'email':
							$metadata[$name] = $this->faker()->email;
							break;

						case 'number':
							$metadata[$name] = $this->faker()->randomNumber();
							break;

						case 'date':
							$metadata[$name] = $this->faker()->unixTime;
							break;

						case 'password':
							$metadata[$name] = elgg_generate_password();
							break;

						case 'location':
							$metadata[$name] = $this->faker()->address;
							$metadata['geo:lat'] = $this->faker()->latitude;
							$metadata['geo:long'] = $this->faker()->longitude;
							break;

						default:
							$metadata[$name] = '';
							break;
					}
					break;
			}
		}

		foreach ($metadata as $key => $value) {
			if (array_key_exists($key, $fields) && $entity instanceof \ElggUser) {
				$entity->setProfileData($key, $value, $this->getRandomAccessId($entity));
			} else {
				$entity->$key = $value;
			}
		}

		return $entity;
	}

	/**
	 * Create an icon for an entity
	 *
	 * @param \ElggEntity $entity Entity
	 *
	 * @return bool
	 */
	public function createIcon(\ElggEntity $entity): bool {

		$icon_location = $this->faker()->image();
		if (empty($icon_location)) {
			return false;
		}

		$result = $entity->saveIconFromLocalFile($icon_location);

		if ($result && $entity instanceof \ElggUser) {
			$since = $this->create_since;
			$this->setCreateSince($entity->time_created);
			
			elgg_create_river_item([
				'view' => 'river/user/default/profileiconupdate',
				'action_type' => 'update',
				'subject_guid' => $entity->guid,
				'object_guid' => $entity->guid,
				'posted' => $this->getRandomCreationTimestamp(),
			]);
			
			$this->create_since = $since;
		}

		return $result;
	}

	/**
	 * Create comments/replies
	 *
	 * @param \ElggEntity $entity Entity to comment on
	 * @param int         $limit  Number of comments to create
	 *
	 * @return int Number of generated comments
	 */
	public function createComments(\ElggEntity $entity, $limit = null): int {

		return elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity, $limit) {
			$tries = 0;
			$success = 0;
	
			if ($limit === null) {
				$limit = $this->faker()->numberBetween(1, 20);
			}
	
			$since = $this->create_since;
			$this->setCreateSince($entity->time_created);
			
			while ($tries < $limit) {
				$comment = new \ElggComment();
				$comment->owner_guid = $this->getRandomUser()->guid ?: $entity->owner_guid;
				$comment->container_guid = $entity->guid;
				$comment->description = $this->faker()->paragraph;
				$comment->time_created = $this->getRandomCreationTimestamp();
	
				$tries++;
				if ($comment->save()) {
					$success++;
				}
			}
	
			$this->create_since = $since;
	
			return $success;
		});
	}

	/**
	 * Create likes
	 *
	 * @param \ElggEntity $entity Entity to like
	 * @param int         $limit  Number of likes to create
	 *
	 * @return int
	 */
	public function createLikes(\ElggEntity $entity, $limit = null): int {

		return elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity, $limit) {
			$success = 0;

			if ($limit === null) {
				$limit = $this->faker()->numberBetween(1, 20);
			}
	
			while ($success < $limit) {
				if ($entity->annotate('likes', true, $entity->access_id, $this->getRandomUser()->guid)) {
					$success++;
				}
			}
			
			return $success;
		});
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
	public function log($msg, $level = LogLevel::NOTICE): void {
		elgg_log($msg, $level);
	}
}
