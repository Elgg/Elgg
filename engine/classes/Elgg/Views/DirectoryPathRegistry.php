<?php

namespace Elgg\Views;

use Elgg\Filesystem\Directory;
use Elgg\Filesystem\File;
use Elgg\Structs\ArrayCollection;
use Elgg\Structs\EntryCollectionMap;
use Elgg\Structs\MapEntry;

/**
 * A ViewFileRegistry backed by the local filesystem.
 * 
 * The directory is expected to be structured like so:
 *
 * ```
 * views/
 *   $viewtype/
 *     $view(.php)?
 * ```
 * 
 * For example, this directory exposes the resources/blog/all in two formats:
 * default and rss.
 * 
 * ```
 * views/
 *   default/
 *     resources/
 *       blog/
 *         all.php
 *   rss/
 *     resources/
 *       blog/
 *         all.php
 * ```
 * 
 * @since 2.0.0
 * @access private
 */
class DirectoryPathRegistry implements PathRegistry {
	/** @var Directory */
	private $dir;
	
	/** @var ViewtypeRegistry */
	private $viewtypes;
	
	/**
	 * Constructor
	 * 
	 * @param Directory        $dir       Filesystem location of the views
	 * @param ViewtypeRegistry $viewtypes For converting from strings to Viewtype instances
	 */
	public function __construct(Directory $dir, ViewtypeRegistry $viewtypes) {
		$this->dir = $dir;
		$this->viewtypes = $viewtypes;
	}
	
	/** @inheritDoc */
	public function getViewtypes() {
		return $this->dir->getDirectories('', false)
	
		// Get the viewtype name (e.g., 'default')
		->map(function(Directory $dir) { return basename($dir->getFullPath()); })
		->map(function(/*string*/ $name) { return $this->viewtypes->get($name); })
		->unique();
	}
	
	/** @inheritDoc */
	private function getPath(/*string*/ $view, Viewtype $viewtype) {
		$path = "{$viewtype->getName()}/$view";
		
		$dynamicFile = $this->dir->getFile("$path.php");
		if ($dynamicFile->exists()) {
			return $dynamicFile;
		}
		
		$staticFile = $this->dir->getFile($path);
		if ($staticFile->exists()) {
			return $staticFile;
		}
		
		return null;
	}
	
	/** @inheritDoc */
	public function forView(/*string*/ $view) {
		if (!$view) {
			return new EntryCollectionMap(new ArrayCollection());
		}

		return new EntryCollectionMap($this->getViewtypes()->map(function(Viewtype $viewtype) use ($view) {
			return new MapEntry($viewtype, $this->getPath($view, $viewtype));
		}));
	}
}