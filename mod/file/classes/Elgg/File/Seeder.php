<?php

namespace Elgg\File;

use Elgg\Database\Seeds\Seed;

/**
 * Add file seed
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
			'subtype' => 'file',
		];

		while ($this->getCount() < $this->limit) {
			$path = $this->faker()->image();

			$filename = pathinfo($path, PATHINFO_FILENAME);

			$file = $this->createObject($attributes, [], ['save' => false]);
			if (!$file instanceof \ElggFile) {
				continue;
			}

			$file->setFilename("file/$filename");
			$file->open('write');
			$file->close();

			copy($path, $file->getFilenameOnFilestore());

			if (!$file->save()) {
				$file->delete();
				continue;
			}

			$file->saveIconFromElggFile($file);

			$this->createComments($file);
			$this->createLikes($file);

			elgg_create_river_item([
				'action_type' => 'create',
				'subject_guid' => $file->owner_guid,
				'object_guid' => $file->guid,
				'target_guid' => $file->container_guid,
				'posted' => $file->time_created,
			]);

			$this->advance();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function unseed() {

		/* @var $files \ElggBatch */
		$files = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'file',
			'metadata_name' => '__faker',
			'limit' => false,
			'batch' => true,
			'batch_inc_offset' => false,
		]);

		/* @var $file \ElggFile */
		foreach ($files as $file) {
			if ($file->delete()) {
				$this->log("Deleted file {$file->guid}");
			} else {
				$this->log("Failed to delete file {$file->guid}");
			}

			$this->advance();
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getType() : string {
		return 'file';
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function getCountOptions() : array {
		return [
			'type' => 'object',
			'subtype' => 'file',
		];
	}
}
