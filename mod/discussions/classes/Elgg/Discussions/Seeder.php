<?php

namespace Elgg\Discussions;

use Elgg\Database\Seeds\Seed;
use Elgg\Exceptions\Seeding\MaxAttemptsException;

/**
 * Add database seed
 *
 * @internal
 */
class Seeder extends Seed {

	protected array $status = [
		'open',
		'closed',
	];

	/**
	 * {@inheritdoc}
	 */
	public function seed() {
		$this->advance($this->getCount());

		while ($this->getCount() < $this->limit) {
			try {
				/* @var $discussion \ElggDiscussion */
				$discussion = $this->createObject([
					'subtype' => 'discussion',
					'container_guid' => $this->getRandomGroup()->guid,
					'status' => $this->getRandomStatus(),
					'excerpt' => $this->faker()->sentence(),
				]);
			} catch (MaxAttemptsException $e) {
				// unable to create a discussion with the given options
				continue;
			}
			
			$this->createComments($discussion);
			$this->createLikes($discussion);

			elgg_create_river_item([
				'action_type' => 'create',
				'subject_guid' => $discussion->owner_guid,
				'object_guid' => $discussion->guid,
				'target_guid' => $discussion->container_guid,
				'posted' => $discussion->time_created,
			]);

			$this->advance();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function unseed() {
		/* @var $discussions \ElggBatch */
		$discussions = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'discussion',
			'metadata_name' => '__faker',
			'limit' => false,
			'batch' => true,
			'batch_inc_offset' => false,
		]);

		/* @var $discussion \ElggDiscussion */
		foreach ($discussions as $discussion) {
			if ($discussion->delete()) {
				$this->log("Deleted discussion {$discussion->guid}");
			} else {
				$this->log("Failed to delete discussion {$discussion->guid}");
				$discussions->reportFailure();
				continue;
			}

			$this->advance();
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getType() : string {
		return 'discussion';
	}

	/**
	 * Returns random discussion status
	 *
	 * @return string
	 */
	public function getRandomStatus(): string {
		$key = array_rand($this->status);

		return $this->status[$key];
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getCountOptions() : array {
		return [
			'type' => 'object',
			'subtype' => 'discussion',
		];
	}
}
