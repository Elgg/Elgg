<?php

namespace Elgg\Bookmarks;

use Elgg\Database\Seeds\Seed;

/**
 * Add bookmarks seed
 *
 * @internal
 */
class Seeder extends Seed {

	/**
	 * {@inheritdoc}
	 */
	public function seed() {
		$created = 0;

		$count_bookmarks = function () use (&$created) {
			if ($this->create) {
				return $created;
			}

			return elgg_count_entities([
				'types' => 'object',
				'subtypes' => 'bookmarks',
				'metadata_names' => '__faker',
			]);
		};

		$this->advance($count_bookmarks());

		$attributes = [
			'subtype' => 'bookmarks',
		];

		while ($count_bookmarks() < $this->limit) {
			$metadata = [
				'address' => $this->faker()->url,
			];

			$bookmark = $this->createObject($attributes, $metadata);
			if (!$bookmark) {
				continue;
			}

			$created++;

			$this->createComments($bookmark);
			$this->createLikes($bookmark);

			elgg_create_river_item([
				'view' => 'river/object/bookmarks/create',
				'action_type' => 'create',
				'subject_guid' => $bookmark->owner_guid,
				'object_guid' => $bookmark->guid,
				'target_guid' => $bookmark->container_guid,
			]);

			$this->advance();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function unseed() {

		$bookmarks = elgg_get_entities([
			'types' => 'object',
			'subtypes' => 'bookmarks',
			'metadata_names' => '__faker',
			'limit' => 0,
			'batch' => true,
		]);

		/* @var $bookmarks \ElggBatch */

		$bookmarks->setIncrementOffset(false);

		foreach ($bookmarks as $bookmark) {
			if ($bookmark->delete()) {
				$this->log("Deleted bookmark $bookmark->guid");
			} else {
				$this->log("Failed to delete bookmark $bookmark->guid");
			}

			$this->advance();
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getType() : string {
		return 'bookmarks';
	}
}
