<?php

namespace Elgg\Database\Seeds\Providers;

use Faker\Provider\Image;

/**
 * Provide images from a local folder for seeding
 */
class LocalImage extends Image {
	
	/**
	 * {@inheritdoc}
	 */
	public static function image($dir = null, $width = 640, $height = 480, $category = null, $fullPath = true, $randomize = true, $word = null, $gray = false) {
		$local_folder = elgg_get_config('seeder_local_image_folder');
		if (empty($local_folder) || !is_dir($local_folder)) {
			return parent::image($dir, $width, $height, $category, $fullPath, $randomize, $word);
		}
		
		$files = [];
		
		// read files in folder
		$dir = new \DirectoryIterator($local_folder);
		/* @var $file \SplFileInfo */
		foreach ($dir as $file) {
			if (!$file->isFile()) {
				continue;
			}
			
			$files[] = $file->getRealPath();
		}
		
		if (empty($files)) {
			return parent::image($dir, $width, $height, $category, $fullPath, $randomize, $word);
		}
		
		// return random file
		$key = array_rand($files);
		
		return $files[$key];
	}
}
