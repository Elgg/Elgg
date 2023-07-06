<?php

namespace Elgg\Bookmarks;

use Elgg\Database\Seeds\Seed;
use Elgg\Exceptions\Seeding\MaxAttemptsException;

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

		while ($this->getCount() < $this->limit) {
			try {
				/* @var $bookmark \ElggBookmark */
				$bookmark = $this->createObject([
					'subtype' => 'bookmarks',
					'address' => $this->faker()->url,
				]);
			} catch (MaxAttemptsException $e) {
				// unable to create a bookmark with the given options
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
				$bookmarks->reportFailure();
				continue;
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
