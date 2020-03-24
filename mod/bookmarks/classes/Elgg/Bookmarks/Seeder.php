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
		$this->advance($this->getCount());

		$attributes = [
			'subtype' => 'bookmarks',
		];

		while ($this->getCount() < $this->limit) {
			$metadata = [
				'address' => $this->faker()->url,
			];

			$bookmark = $this->createObject($attributes, $metadata);
			if (!$bookmark) {
				continue;
			}

			$this->createComments($bookmark);
			$this->createLikes($bookmark);

			elgg_create_river_item([
				'view' => 'river/object/bookmarks/create',
				'action_type' => 'create',
				'subject_guid' => $bookmark->owner_guid,
				'object_guid' => $bookmark->guid,
				'target_guid' => $bookmark->container_guid,
				'posted' => $bookmark->time_created,
			]);

			$this->advance();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function unseed() {

		/* @var $bookmarks \ElggBatch */
		$bookmarks = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'bookmarks',
			'metadata_name' => '__faker',
			'limit' => false,
			'batch' => true,
			'batch_inc_offset' => false,
		]);

		/* @var $boolmark \ElggBookmark */
		foreach ($bookmarks as $bookmark) {
			if ($bookmark->delete()) {
				$this->log("Deleted bookmark {$bookmark->guid}");
			} else {
				$this->log("Failed to delete bookmark {$bookmark->guid}");
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
	
	/**
	 * {@inheritDoc}
	 */
	protected function getCountOptions() : array {
		return [
			'type' => 'object',
			'subtype' => 'bookmarks',
		];
	}
}
