<?php

namespace Elgg\TheWire;

use Elgg\Database\Seeds\Seed;

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
		
		while ($this->getCount() < $this->limit) {
			$owner = $this->getRandomUser();

			$wire_guid = thewire_save_post($this->faker()->text($max_chars), $owner->guid, $this->getRandomAccessId($owner));
			if (!$wire_guid) {
				continue;
			}
			
			/* @var $post \ElggWire */
			$post = get_entity($wire_guid);
			$post->__faker = true;

			$this->createLikes($post);

			$num_replies = $this->faker->numberBetween(0, 5);
			$exclude = [
				$owner->guid,
			];
			for ($i = 0; $i < $num_replies; $i++) {
				$reply_owner = $this->getRandomUser($exclude);
				$exclude[] = $reply_owner->guid;
				
				$reply_guid = thewire_save_post($this->faker()->text($max_chars), $reply_owner->guid, $this->getRandomAccessId($reply_owner), $post->guid);
				if (!$reply_guid) {
					continue;
				}
				
				/* @var $post \ElggWire */
				$reply = get_entity($reply_guid);
				$reply->__faker = true;
				
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
			'types' => 'object',
			'subtypes' => 'thewire',
			'metadata_names' => '__faker',
			'limit' => 0,
			'batch' => true,
			'batch_inc_offset' => false,
		]);

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
