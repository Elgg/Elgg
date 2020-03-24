<?php

namespace Elgg\Blog;

use Elgg\Database\Seeds\Seed;

/**
 * Add blog seed
 *
 * @internal
 */
class Seeder extends Seed {

	private $status = [
		'draft',
		'published',
	];

	/**
	 * {@inheritdoc}
	 */
	public function seed() {
		$this->advance($this->getCount());

		$attributes = [
			'subtype' => 'blog',
		];

		while ($this->getCount() < $this->limit) {
			$metadata = [
				'status' => $this->getRandomStatus(),
				'comments_on' => $this->faker()->boolean() ? 'On' : 'Off',
				'excerpt' => $this->faker()->sentence(),
			];

			$blog = $this->createObject($attributes, $metadata);
			if (!$blog) {
				continue;
			}

			$this->createComments($blog);
			$this->createLikes($blog);

			if ($blog->status === 'draft') {
				$blog->future_access = $blog->access_id;
				$blog->access_id = ACCESS_PRIVATE;
			}

			if ($blog->status === 'published') {
				elgg_create_river_item([
					'view' => 'river/object/blog/create',
					'action_type' => 'create',
					'subject_guid' => $blog->owner_guid,
					'object_guid' => $blog->guid,
					'target_guid' => $blog->container_guid,
					'posted' => $blog->time_created,
				]);

				elgg_trigger_event('publish', 'object', $blog);
			}

			if ($this->faker()->boolean()) {
				$blog->annotate('blog_auto_save', $this->faker()->text(500), ACCESS_PRIVATE, $blog->owner_guid);
			}

			if ($this->faker()->boolean()) {
				$blog->annotate('blog_revision', $blog->description, ACCESS_PRIVATE, $blog->owner_guid);
				$blog->description = $this->faker()->text(500);
			}

			$blog->save();

			$this->advance();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function unseed() {

		/* @var $blogs \ElggBatch */
		$blogs = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'blog',
			'metadata_name' => '__faker',
			'limit' => false,
			'batch' => true,
			'batch_inc_offset' => false,
		]);

		/* @var $blog \ElggBlog */
		foreach ($blogs as $blog) {
			if ($blog->delete()) {
				$this->log("Deleted blog {$blog->guid}");
			} else {
				$this->log("Failed to delete blog {$blog->guid}");
			}

			$this->advance();
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getType() : string {
		return 'blog';
	}

	/**
	 * Returns random blog status
	 * @return string
	 */
	public function getRandomStatus() {
		$key = array_rand($this->status, 1);

		return $this->status[$key];
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getCountOptions() : array {
		return [
			'type' => 'object',
			'subtype' => 'blog',
		];
	}
}
