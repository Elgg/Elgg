<?php

namespace Elgg\File;

use Elgg\Database\Seeds\Seed;
use Elgg\Project\Paths;

/**
 * Add file seed
 *
 * @access private
 */
class Seeder extends Seed {

	/**
	 * {@inheritdoc}
	 */
	public function seed() {

		$count_files = function () {
			return elgg_get_entities([
				'types' => 'object',
				'subtypes' => 'file',
				'metadata_names' => '__faker',
				'count' => true,
			]);
		};

		while ($count_files() < $this->limit) {
			$path = $this->faker()->image();

			$filename = pathinfo($path, PATHINFO_FILENAME);

			$attributes = [
				'subtype' => 'file',
			];

			$file = $this->createObject($attributes, [], ['save' => false]);
			/* @var $file \ElggFile */

			if (!$file) {
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
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function unseed() {

		$files = elgg_get_entities([
			'types' => 'object',
			'subtypes' => 'file',
			'metadata_names' => '__faker',
			'limit' => 0,
			'batch' => true,
		]);

		/* @var $files \ElggBatch */

		$files->setIncrementOffset(false);

		foreach ($files as $file) {
			if ($file->delete()) {
				$this->log("Deleted file $file->guid");
			} else {
				$this->log("Failed to delete file $file->guid");
			}
		}
	}

}
