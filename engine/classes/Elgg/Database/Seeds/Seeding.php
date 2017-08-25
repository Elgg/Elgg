<?php
/**
 *
 */

namespace Elgg\Database\Seeds;

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
	 * Returns an instance of faker
	 * @return \Faker\Generator
	 */
	public function faker() {
		return _elgg_services()->faker;
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
			$defaults = [
				'password' => $this->faker()->password(10),
				'name' => $name = $this->faker()->name,
				'username' => $username = $this->generateUsername($name),
				'email' => $this->generateEmail($username),
				'validated' => true,
				'validation_method' => 'seeder',
				'notification:method:email' => true,
				'notification:method:site' => true,
				'subtype' => null,
				'access_id' => ACCESS_PUBLIC,
				'owner_guid' => 0,
				'container_guid' => 0,
			];

			$fields = $this->fillProfileFields(elgg_extract('profile_fields', $options, []));

			$data = array_merge($defaults, $fields, $metadata, $attributes);
			$data['__faker'] = true;

			$admin = elgg_extract('admin', $data);
			$banned = elgg_extract('banned', $data);
			unset($data['admin']);
			unset($data['banned']);

			$user = false;

			try {
				$class = get_subtype_class('user', $data['subtype']);
				if ($class && class_exists($class)) {
					$user = new $class();
				} else {
					$user = new ElggUser();
				}

				foreach ($data as $key => $value) {
					if (is_callable($value)) {
						$user->$key = call_user_func($value, $user);
					} else {
						$user->$key = $value;
					}
				}

				if (!$user->save()) {
					throw new \RegistrationException("Can not save user");
				}

				if ($admin == 'yes') {
					$user->makeAdmin();
				}

				if ($banned == 'yes') {
					$user->ban();
				}

				$this->log("Created new user $user->name [guid: $user->guid]");

				return $user;
			} catch (\RegistrationException $e) {
				if ($user && $user->guid) {
					$user->delete();
				}

				$attr_log = print_r($data, true);
				$this->log("User creation failed with message {$e->getMessage()} [data: $attr_log]");

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

			$defaults = [
				'access_id' => ACCESS_PUBLIC,
				'content_access_mode' => ElggGroup::CONTENT_ACCESS_MODE_UNRESTRICTED,
				'membership' => ACCESS_PUBLIC,
				'name' => trim($this->faker()->sentence(), '.'),
				'description' => $this->faker()->text($this->faker()->numberBetween(500, 1000)),
				'owner_guid' => $owner_guid = $this->getValidOwnerGuid(),
				'container_guid' => $owner_guid,
				'featured_group' => $this->faker()->boolean(20) ? 'yes' : 'no',
				'subtype' => null,
			];

			$fields = $this->fillProfileFields(elgg_extract('profile_fields', $options, []));

			$tool_options = elgg_extract('group_tools_options', $options, []);
			if ($tool_options) {
				foreach ($tool_options as $group_option) {
					$option_toggle_name = $group_option->name . "_enable";
					$option_default = $group_option->default_on ? 'yes' : 'no';
					$metadata[$option_toggle_name] = $option_default;
				}
			}

			$data = array_merge($defaults, $fields, $metadata, $attributes);
			$data['__faker'] = true;

			$class = get_subtype_class('group', $data['subtype']);
			if ($class && class_exists($class)) {
				$group = new $class();
			} else {
				$group = new ElggGroup();
			}

			foreach ($data as $key => $value) {
				if (is_callable($value)) {
					$group->$key = call_user_func($value, $group);
				} else {
					$group->$key = $value;
				}
			}

			if (!$group->save()) {
				return false;
			}

			if ($group->access_id == ACCESS_PRIVATE) {
				$group->access_id = $group->group_acl;
				$group->save();
			}

			$owner = $group->getOwnerEntity();
			if ($owner) {
				$group->join($owner);
			}

			$this->log("Created new group $group->name [guid: $group->guid]");

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

			$defaults = [
				'access_id' => ACCESS_PUBLIC,
				'subtype' => $this->getRandomSubtype(),
				'title' => trim($this->faker()->sentence(), '.'),
				'description' => $this->faker()->text($this->faker()->numberBetween(500, 1000)),
				'owner_guid' => $owner_guid = $this->getValidOwnerGuid(),
				'container_guid' => $owner_guid,
				'tags' => $this->faker()->words(10),
			];

			$fields = $this->fillProfileFields(elgg_extract('profile_fields', $options, []));

			$data = array_merge($defaults, $fields, $metadata, $attributes);
			$data['__faker'] = true;

			$class = get_subtype_class('object', $data['subtype']);
			if ($class && class_exists($class)) {
				$object = new $class();
			} else {
				$object = new ElggObject();
			}

			foreach ($data as $key => $value) {
				if (is_callable($value)) {
					$object->$key = call_user_func($value, $object);
				} else {
					$object->$key = $value;
				}
			}

			if (!$object->save()) {
				return false;
			}

			$type_str = elgg_echo("item:object:{$object->getSubtype()}");
			$this->log("Created new item in $type_str $object->title [guid: $object->guid]");

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
	 * Returns random GUID for owner
	 * @return int
	 */
	public function getValidOwnerGuid() {
		if (elgg_is_logged_in()) {
			return elgg_get_logged_in_user_guid();
		}

		$random = $this->getRandomUser();
		if ($random) {
			return $random->guid;
		}

		$new = $this->createUser();

		return $new->guid;
	}

	/**
	 * Returns random fake entity
	 *
	 * @param mixed $type    Entity types
	 * @param array $exclude Entities to exclude
	 *
	 * @return bool|ElggEntity
	 */
	public function getRandomEntity($type = null, array $exclude = []) {
		$exclude[] = 0;
		$exclude_in = implode(',', array_map(function ($e) {
			return (int) $e;
		}, $exclude));

		$entities = elgg_get_entities_from_metadata([
			'types' => $type,
			'metadata_names' => ['__faker'],
			'limit' => 1,
			'wheres' => [
				"e.guid NOT IN ($exclude_in)",
			],
			'order_by' => 'RAND()',
		]);

		return $entities ? $entities[0] : false;
	}

	/**
	 * Returns random fake user
	 *
	 * @param int[] $exclude GUIDs to exclude
	 *
	 * @return ElggUser|false
	 */
	public function getRandomUser(array $exclude = []) {
		return $this->getRandomEntity('user', $exclude);

	}

	/**
	 * Returns random fake group
	 *
	 * @param int[] $exclude GUIDs to exclude
	 *
	 * @return ElggGroup|false
	 */
	public function getRandomGroup(array $exclude = []) {
		return $this->getRandomEntity('group', $exclude);
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
			'container_guid' => $container->guid,
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
	public function generateUsername($base_name = 'user') {

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
		$base_name = str_replace('.', '', $base_name);

		$ia = elgg_set_ignore_access(true);

		$ha = access_get_show_hidden_status();
		access_show_hidden_entities(true);

		while (!$available) {
			$suffix = (new \ElggCrypto())->getRandomString(4);
			$base_name .= $suffix;
			$user = get_user_by_username($base_name);
			$available = !$user;
			try {
				if ($available) {
					validate_username($base_name);
				}
			} catch (\Exception $e) {
				// Do nothing
			}
		}

		access_show_hidden_entities($ha);
		elgg_set_ignore_access($ia);

		return strtolower($base_name);
	}

	/**
	 * Generate an email from username
	 *
	 * @param string $username Username
	 *
	 * @return string
	 */
	public function generateEmail($username) {
		return "{$username}@{$this->faker()->domainName}";
	}

	/**
	 * Populate fields with random data
	 *
	 * @param array $fields An array of profile fields in $name => $input_type format
	 *
	 * @return array
	 */
	public function fillProfileFields($fields = []) {

		$data = [];

		if (empty($fields)) {
			return $data;
		}

		foreach ($fields as $name => $type) {
			if (in_array($name, [
				'phone',
				'mobile'
			])) {
				$data[$name] = $this->faker()->phoneNumber;
			} else {
				switch ($type) {
					case 'plaintext' :
					case 'longtext' :
						$data[$name] = $this->faker()->text($this->faker()->numberBetween(500, 1000));
						break;

					case 'text' :
						$data[$name] = $this->faker()->sentence;
						break;

					case 'tags' :
						$data[$name] = $this->faker()->words(10);
						break;

					case 'url' :
						$data[$name] = $this->faker()->url;

					case 'email' :
						$data[$name] = $this->faker()->email;
						break;

					case 'number' :
						$data[$name] = $this->faker()->randomNumber();
						break;

					case 'date' :
						$data[$name] = $this->faker()->unixTime;
						break;

					case 'password' :
						$data[$name] = generate_random_cleartext_password();
						break;

					case 'location' :
						$data[$name] = $this->faker()->address;
						$data['geo:lat'] = $this->faker()->latitude;
						$data['geo:long'] = $this->faker()->longitude;
						break;

					case 'email' :
						$data[$name] = $this->faker()->address;
						$data['geo:lat'] = $this->faker()->latitude;
						$data['geo:long'] = $this->faker()->longitude;
						break;

					default :
						$data[$name] = '';
						break;
				}
			}
		}

		return $data;
	}

	/**
	 * Create an icon for an entity
	 *
	 * @param ElggEntity $entity Entity
	 *
	 * @return bool
	 */
	public function createIcon(ElggEntity $entity) {

		$icon_url = $this->faker()->imageURL();

		$file_contents = file_get_contents($icon_url);

		$tmp = new \ElggFile();
		$tmp->owner_guid = $entity->guid;
		$tmp->setFilename("tmp/icon_src.jpg");
		$tmp->open('write');
		$tmp->write($file_contents);
		$tmp->close();

		$result = $entity->saveIconFromElggFile($tmp);

		$tmp->delete();

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
			$comment->subtype = $entity->getSubtype() == 'discussion' ? 'discussion_reply' : 'comment';
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