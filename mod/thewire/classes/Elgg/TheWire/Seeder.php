<?php

namespace Elgg\TheWire;

use Elgg\Database\Seeds\Seed;
use Elgg\Database\Update;

/**
 * Add the wire seed
 *
 * @internal
 */
class Seeder extends Seed {

	/**
	 * {@inheritdoc}
	 */
	public function seed() {
		$this->advance($this->getCount());

		$max_chars = (int) elgg_get_plugin_setting('limit', 'thewire');
		if ($max_chars < 1) {
			// 0 = unlimited
			$max_chars = 500;
		}
		
		$fix_post = function(int $guid) {
			$entity = get_entity($guid);
			if (!$entity instanceof \ElggWire) {
				return false;
			}
			
			// change time created
			$entity->time_created = $this->getRandomCreationTimestamp();
			$entity->save();
			
			// add faker metadata
			$entity->__faker = true;
			
			// fix river item
			$river = elgg_get_river([
				'view' => 'river/object/thewire/create',
				'action_type' => 'create',
				'subject_guid' => $entity->owner_guid,
				'object_guid' => $entity->guid,
			]);
			/* @var $item \ElggRiverItem */
			foreach ($river as $item) {
				$update = Update::table('river');
				$update->set('posted', $update->param($entity->time_created, ELGG_VALUE_TIMESTAMP))
					->where($update->compare('id', '=', $item->id, ELGG_VALUE_ID));
				
				elgg()->db->updateData($update);
			}
			
			return $entity;
		};
		
		while ($this->getCount() < $this->limit) {
			$owner = $this->getRandomUser([], true);

			$wire_guid = thewire_save_post($this->faker()->text($max_chars), $owner->guid, $this->getRandomAccessId($owner));
			if ($wire_guid === false) {
				continue;
			}
			
			/* @var $post \ElggWire */
			$post = $fix_post($wire_guid);

			$this->createLikes($post);

			$num_replies = $this->faker->numberBetween(0, 5);
			$exclude = [
				$owner->guid,
			];
			for ($i = 0; $i < $num_replies; $i++) {
				$reply_owner = $this->getRandomUser($exclude, true);
				$exclude[] = $reply_owner->guid;
				
				$reply_guid = thewire_save_post($this->faker()->text($max_chars), $reply_owner->guid, $this->getRandomAccessId($reply_owner), $post->guid);
				if ($reply_guid === false) {
					continue;
				}
				
				$fix_post($reply_guid);
				
				$this->advance();
			}
			
			$this->advance();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function unseed() {

		/* @var $entities \ElggBatch */
		$entities = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'thewire',
			'metadata_name' => '__faker',
			'limit' => false,
			'batch' => true,
			'batch_inc_offset' => false,
		]);

		/* @var $entity \ElggWire */
		foreach ($entities as $entity) {
			if ($entity->delete()) {
				$this->log("Deleted wire post {$entity->guid}");
			} else {
				$this->log("Failed to delete wire post {$entity->guid}");
			}

			$this->advance();
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getType() : string {
		return 'thewire';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getCountOptions() : array {
		return [
			'type' => 'object',
			'subtype' => 'thewire',
		];
	}
}
