<?php

namespace Elgg\TheWire;

use Elgg\Database\RiverTable;
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
		
		while ($this->getCount() < $this->limit) {
			$owner = $this->getRandomUser();
			
			$post = $this->createWirePost($owner);
			if (empty($post)) {
				continue;
			}
			
			$post->wire_thread = $post->guid; // first post in this thread
			
			$this->createLikes($post);

			$num_replies = $this->faker->numberBetween(0, 5);
			$exclude = [
				$owner->guid,
			];
			for ($i = 0; $i < $num_replies; $i++) {
				$reply_owner = $this->getRandomUser($exclude, true);
				$exclude[] = $reply_owner->guid;
				
				$reply_post = $this->createWirePost($reply_owner);
				if (empty($reply_post)) {
					continue;
				}
				
				$reply_post->reply = true;
				
				$reply_post->addRelationship($post->guid, 'parent');
				$reply_post->wire_thread = get_entity($post->guid)->wire_thread;
				
				$this->advance();
			}
			
			$this->advance();
		}
	}
	
	/**
	 * Helper function to create a wire post
	 *
	 * @param \ElggUser $owner the owner of the wire post
	 *
	 * @return null|\ElggWire
	 */
	protected function createWirePost(\ElggUser $owner): ?\ElggWire {
		$max_chars = (int) elgg_get_plugin_setting('limit', 'thewire');
		if ($max_chars < 1) {
			// 0 = unlimited
			$max_chars = 500;
		}
		
		$post = $this->createObject([
			'subtype' => 'thewire',
			'title' => false,
			'description' => $this->faker()->text($max_chars),
			'tags' => false,
			'owner_guid' => $owner->guid,
			'access_id' => $this->getRandomAccessId($owner),
		]);
		
		if (!$post instanceof \ElggWire) {
			return null;
		}
		
		elgg_create_river_item([
			'action_type' => 'create',
			'object_guid' => $post->guid,
			'target_guid' => $post->container_guid,
		]);
		
		return $post;
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
				$entities->reportFailure();
				continue;
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
