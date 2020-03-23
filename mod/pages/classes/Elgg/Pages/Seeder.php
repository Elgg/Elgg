<?php

namespace Elgg\Pages;

use Elgg\Database\Seeds\Seed;

/**
 * Add page seed
 *
 * @internal
 */
class Seeder extends Seed {

	/**
	 * {@inheritdoc}
	 */
	public function seed() {
		$this->advance($this->getCount());

		$create_page = function () {
			$metadata = [
				'write_access_id' => ACCESS_LOGGED_IN,
			];

			$attributes = [
				'subtype' => 'page',
			];

			$page = $this->createObject($attributes, $metadata);
			if (!$page) {
				return;
			}

			$this->createComments($page);
			$this->createLikes($page);

			$page->annotate('page', $this->faker()->paragraph(), $page->access_id, $page->owner_guid);
			$page->annotate('page', $this->faker()->paragraph(), $page->access_id, $page->owner_guid);

			$page->annotate('page', $page->description, $page->access_id, $page->owner_guid);

			elgg_create_river_item([
				'action_type' => 'create',
				'object_guid' => $page->guid,
			]);

			$this->advance();
			
			return $page;
		};

		while ($this->getCount() < $this->limit) {
			$page = $create_page();
			if (!$page instanceof \ElggPage) {
				continue;
			}

			$sub_page_limit = $this->faker->numberBetween(0, 5);
			for ($i = 0; $i < $sub_page_limit; $i++) {
				$subpage = $create_page();
				if ($subpage instanceof \ElggPage) {
					$subpage->setParentEntity($page);
					$subpage->save();
				}
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function unseed() {

		$pages = elgg_get_entities([
			'types' => 'object',
			'subtypes' => 'page',
			'metadata_names' => '__faker',
			'limit' => 0,
			'batch' => true,
		]);

		/* @var $pages \ElggBatch */

		$pages->setIncrementOffset(false);

		foreach ($pages as $page) {
			if ($page->delete()) {
				$this->log("Deleted page $page->guid");
			} else {
				$this->log("Failed to delete page $page->guid");
			}

			$this->advance();
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getType() : string {
		return 'page';
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getCountOptions() : array {
		return [
			'type' => 'object',
			'subtype' => 'page',
		];
	}
}
