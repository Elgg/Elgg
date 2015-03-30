<?php
namespace Elgg\Filesystem\Adapter;
use Gaufrette\Filesystem;
use Gaufrette\File;
use Gaufrette\Adapter\Local as GaufretteLocal;
use Elgg\EntityDirLocator;

class Disk extends Filesystem implements Adapter {
	private $config;
	public function __construct(array $config) {
		$this->config = $config;
		if (!isset($config['path'])) {
			throw new \InvalidArgumentException("Config passed to Filesystem\Adapter\Disk must have root_dir");
		}

		$adapter = new GaufretteLocal($config['path'], false);
		parent::__construct($adapter);
	}
	
	/**
	 * 
	 * @param type $name
	 * @return File
	 */
	public function file($name) {
		return new File($name, $this);
	}
	
	/**
	 * 
	 * @param type $path
	 * @return \self
	 */
	public function directory($path) {
		// path is normalized by gaufrette
		$config = $this->config;
		$config['path'] = "{$config['path']}/$path";
		return new self($config);
	}
	
	/**
	 * @todo Inject via $config?
	 * 
	 * @param type $guid
	 * @return type
	 */
	public static function getPathPrefix($guid) {
		$locator = new EntityDirLocator($guid);
		return "$locator";
	}
}