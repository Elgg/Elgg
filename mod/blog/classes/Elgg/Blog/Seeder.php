<?php

namespace Elgg\Blog;

use Elgg\Database\Seeds\Seed;
use Elgg\Exceptions\Seeding\MaxAttemptsException;

/**
 * Add blog seed
 *
 * @internal
 */
class Seeder extends Seed {

	protected array $status = [
		'draft',
		'published',
	];

	/**
	 * {@inheritdoc}
	 */
	public function seed() {
		$this->advance($this->getCount());

		while ($this->getCount() < $this->limit) {
			try {
				/* @var $blog \ElggBlog */
				$blog = $this->createObject([
					'subtype' => 'blog',
					'status' => $this->getRandomStatus(),
					'comments_on' => $this->faker()->boolean() ? 'On' : 'Off',
					'excerpt' => $this->faker()->sentence(),
				]);
			} catch (MaxAttemptsException $e) {
				// unable to create a blog with the given options
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
				$blogs->reportFailure();
				continue;
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
			'subtype' => 'blog',
		];
	}
}
