<?php
/**
 *
 */

namespace Elgg\Database\Seeds;

use Elgg\Project\Paths;
use ElggEntity;
use ElggGroup;
use ElggObject;
use ElggUser;
use Exception;
use Faker\Factory;

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
				$metadata['email'] = "{$metadata['username']}@{$this->getEmailDomain()}";
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
						$user->unban('Unbanned by seeder');
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

		$ia = elgg_set_ignore_access(true);

		$user = false;
		while (!$user instanceof \ElggUser) {
			$user = $create();
		}

		elgg_set_ignore_access($ia);

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
			$metadata['__faker'] = true;

			if (empty($attributes['access_id'])) {
				$attributes['access_id'] = ACCESS_PUBLIC;
			}

			if (empty($metadata['content_access_mode'])) {
				$metadata['content_access_mode'] = ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED;
			}

			if (empty($metadata['membership'])) {
				$metadata['membership'] = ACCESS_PUBLIC;
			}

			if (empty($metadata['name'])) {
				$metadata['name'] = $this->faker()->sentence();
			}

			if (empty($metadata['description'])) {
				$metadata['description'] = $this->faker()->text($this->faker()->numberBetween(500, 1000));
			}

			if (empty($attributes['owner_guid'])) {
				$user = elgg_get_logged_in_user_entity();
				if (!$user) {
					$user = $this->getRandomUser();
				}
				if (!$user) {
					$user = $this->createUser();
				}

				$attributes['owner_guid'] = $user->guid;
			}

			if (empty($attributes['container_guid'])) {
				$attributes['container_guid'] = $attributes['owner_guid'];
			}

			if (empty($attributes['subtype'])) {
				$attributes['subtype'] = 'group';
			}

			$owner = get_entity($attributes['owner_guid']);
			if (!$owner) {
				return false;
			}

			$container = get_entity($attributes['container_guid']);
			if (!$container) {
				return false;
			}

			$tool_options = elgg_extract('group_tools_options', $options, []);
			if ($tool_options) {
				foreach ($tool_options as $group_option) {
					$option_toggle_name = $group_option->name . "_enable";
					$option_default = $group_option->default_on ? 'yes' : 'no';
					$metadata[$option_toggle_name] = $option_default;
				}
			}

			if ($this->faker()->boolean(20)) {
				$metadata['featured_group'] = 'yes';
			}

			$group = new ElggGroup();
			foreach ($attributes as $name => $value) {
				$group->$name = $value;
			}

			$profile_fields = elgg_extract('profile_fields', $options, []);
			$group = $this->populateMetadata($group, $profile_fields, $metadata);

			$group->save();

			if ($group->access_id == ACCESS_PRIVATE) {
				$acls = $group->getOwnedAccessCollections(['subtype' => 'group_acl']);
				if ($acls) {
					$group->access_id = $acls[0]->id;
					$group->save();
				}
			}

			$group->join(get_entity($attributes['owner_guid']));

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

		$ia = elgg_set_ignore_access(true);

		$group = false;
		while (!$group instanceof \ElggGroup) {
			$group = $create();
		}

		elgg_set_ignore_access($ia);

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

			if (empty($properties['container_guid'])) {
				if (isset($properties['owner_guid'])) {
					$properties['container_guid'] = $properties['owner_guid'];
				} else {
					$container = elgg_get_logged_in_user_entity();
					if (!$container) {
						$container = $this->getRandomUser();
					}
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

			if (empty($properties['owner_guid'])) {
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

		$ia = elgg_set_ignore_access(true);

		$object = false;
		while (!$object instanceof \ElggObject) {
			$object = $create();
		}

		elgg_set_ignore_access($ia);

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
		$exclude_in = implode(',', array_map(function ($e) {
			return (int) $e;
		}, $exclude));

		$users = elgg_get_entities([
			'types' => 'user',
			'metadata_names' => ['__faker'],
			'limit' => 1,
			'wheres' => [
				"e.guid NOT IN ($exclude_in)",
			],
			'order_by' => 'RAND()',
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
		$exclude_in = implode(',', array_map(function ($e) {
			return (int) $e;
		}, $exclude));

		$groups = elgg_get_entities([
			'types' => 'group',
			'metadata_names' => ['__faker'],
			'limit' => 1,
			'wheres' => [
				"e.guid NOT IN ($exclude_in)",
			],
			'order_by' => 'RAND()',
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
	 * @param string $base_name Display name, email or other prefix to use as basis
	 *
	 * @return string
	 */
	public function getRandomUsername($base_name = 'user') {

		$available = false;

		$base_name = iconv('UTF-8', 'ASCII//TRANSLIT', $base_name);
		$blacklist = '/[\x{0080}-\x{009f}\x{00a0}\x{2000}-\x{200f}\x{2028}-\x{202f}\x{3000}\x{e000}-\x{f8ff}]/u';
		$blacklist2 = [
			' ',
			'\'',
			'/',
			'\\',
			'"',
			'*',
			'&',
			'?',
			'#',
			'%',
			'^',
			'(',
			')',
			'{',
			'}',
			'[',
			']',
			'~',
			'?',
			'<',
			'>',
			';',
			'|',
			'¬',
			'`',
			'@',
			'-',
			'+',
			'='
		];

		$base_name = preg_replace($blacklist, '', $base_name);
		$base_name = str_replace($blacklist2, '', $base_name);
		$base_name = str_replace('.', '_', $base_name);

		$ia = elgg_set_ignore_access(true);

		$ha = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		$minlength = elgg_get_config('minusername') ? : 8;
		if ($base_name) {
			$fill = $minlength - strlen($base_name);
		} else {
			$fill = 8;
		}

		$separator = '';

		if ($fill > 0) {
			$suffix = (new \ElggCrypto())->getRandomString($fill);
			$base_name = "$base_name$separator$suffix";
		}

		$iterator = 0;
		while (!$available) {
			if ($iterator > 0) {
				$base_name = "$base_name$separator$iterator";
			}
			$user = get_user_by_username($base_name);
			$available = !$user;
			try {
				if ($available) {
					validate_username($base_name);
				}
			} catch (\Exception $e) {
				if ($iterator >= 10) {
					// too many failed attempts
					$base_name = (new \ElggCrypto())->getRandomString(8);
				}
			}

			$iterator++;
		}

		access_show_hidden_entities($ha);
		elgg_set_ignore_access($ia);

		return strtolower($base_name);
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

		$ia = elgg_set_ignore_access(true);

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

		elgg_set_ignore_access($ia);

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

		$ia = elgg_set_ignore_access(true);

		$success = 0;

		if (!$limit) {
			$limit = $this->faker()->numberBetween(1, 20);
		}

		while ($success < $limit) {
			if ($entity->annotate('likes', true, $entity->access_id, $this->getRandomUser()->guid)) {
				$success++;
			}
		}

		elgg_set_ignore_access($ia);

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
	public function log($msg, $level = 'NOTICE') {
		elgg_log($msg, $level);
	}

}