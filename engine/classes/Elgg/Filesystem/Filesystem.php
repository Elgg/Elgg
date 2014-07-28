<?php
namespace Elgg\Filesystem;

use League\Flysystem;

/**
 * A simple filesystem abstraction that can handle several different backends.
 * 
 * @package    Elgg.Core
 * @subpackage Filesystem
 * @since      1.10.0
 * 
 * @access private
 */
class Filesystem {
	
	/** @var Flysystem\Filesystem */
	private $filesystem;
	
	/**
	 * Constructor
	 * 
	 * @param Flysystem\Filesystem $filesystem The filesystem
	 */
	public function __construct(Flysystem\Filesystem $filesystem) {
		$this->filesystem = $filesystem;
	}
	
	/**
	 * Get a list of files in the given directory path.
	 * 
	 * @param string $path The directory path
	 * 
	 * @return File[] 
	 */
	public function getFiles($path) {
		$handlerinfos = $this->filesystem->listContents($path);
		
		$fileinfos = array_filter($handlerinfos, function($handlerinfo) {
			return $handlerinfo['type'] == 'file';
		});

		return array_map(function($fileinfo) use ($filesystem) {
			return new File($filesystem, $fileinfo['path']);
		}, $fileinfos);
	}

	/**
	 * Whether this filesystem has a file at the given location.
	 * 
	 * @param string $path The relative path within this filesystem
	 * 
	 * @return boolean False for directories or non-existent.
	 */
	public function hasFile($path) {
		$file = $this->filesystem->get($path);
		return $file->isFile();
	}
}
