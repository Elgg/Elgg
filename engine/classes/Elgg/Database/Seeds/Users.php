<?php

namespace Elgg\Database\Seeds;

/**
 * Seed users
 *
 * @access private
 */
class Users extends Seed {

	/**
	 * {@inheritdoc}
	 */
	public function seed() {

		$count_users = function () {
			return elgg_get_entities_from_metadata([
				'types' => 'user',
				'metadata_names' => '__faker',
				'count' => true,
			]);
		};

		$count_friends = function ($user) {
			return elgg_get_entities_from_relationship([
				'types' => 'user',
				'relationship' => 'friend',
				'relationship_guid' => $user->guid,
				'inverse_relationship' => false,
				'metadata_names' => '__faker',
				'count' => true,
			]);
		};

		$exclude = [];

		while ($count_users() < $this->limit) {
			$user = $this->getRandomUser($exclude);
			if (!$user) {
				$user = $this->createUser();
				if (!$user) {
					continue;
				}
			}

			$this->createIcon($user);

			$exclude[] = $user->guid;

			// Friend the user other members
			// Create a friend access collection and add some random friends to it

			if ($count_friends($user)) {
				continue;
			}

			$collection_id = create_access_collection('Best Fake Friends Collection', $user->guid);
			if ($collection_id) {
				$this->log("Created new friend collection for user $user->name [collection_id: $collection_id]");
			}

			$friends_limit = $this->faker()->numberBetween(5, 10);

			$friends_exclude = [$user->guid];
			while ($count_friends($user) < $friends_limit) {
				$friend = $this->getRandomUser($friends_exclude);
				if (!$friend) {
					$this->createUser();
					if (!$friend) {
						continue;
					}
				}

				$friends_exclude[] = $friend->guid;

				if ($user->addFriend($friend->guid, true)) {
					$this->log("User $user->name [guid: $user->guid] friended user $friend->name [guid: $friend->guid]");
					if ($this->faker()->boolean() && $collection_id) {
						add_user_to_access_collection($friend->guid, $collection_id);
					}
				}
			}
		}

	}

	/**
	 * {@inheritdoc}
	 */
	public function unseed() {

		$users = elgg_get_entities_from_metadata([
			'types' => 'user',
			'metadata_names' => '__faker',
			'limit' => 0,
			'batch' => true,
		]);

		/* @var $users \ElggBatch */

		$users->setIncrementOffset(false);

		foreach ($users as $user) {
			if ($user->delete()) {
				$this->log("Deleted user $user->guid");
			} else {
				$this->log("Failed to delete user $user->guid");
			}
		}
	}

}
