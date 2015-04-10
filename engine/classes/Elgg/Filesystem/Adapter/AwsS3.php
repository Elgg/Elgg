<?php
namespace Elgg\Filesystem\Adapter;
use Gaufrette\Filesystem;
use Gaufrette\File;
use Gaufrette\Adapter\AwsS3 as GaufretteAwsS3;

/**
 * Data storage on AWS S3
 * 
 */
class AwsS3 implements Adapter {
	/**
	 * @var Filesystem
	 */
	private $fs;
	
	/**
	 * @var GaufretteAwsS3
	 */
	private $adapter;
	
	/**
	 * The current path
	 *
	 * @var string
	 */
	private $path;
	
	/**
	 * Create a new instance of a filesystem on AWS S3.
	 * 
	 * @param GaufretteAwsS3 $adapter The AWS S3 adapter, instantiated with an S3Client.
	 * @param string         $path    The current path
	 */
	public function __construct(GaufretteAwsS3 $adapter, $path = '') {
		$this->adapter = $adapter;
		$this->path = $path;
		$this->fs = new Filesystem($adapter);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function file($name) {
		return new File($this->fullPath($name), $this->fs);
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function directory($path) {
		return new self($this->adapter, $this->fullPath($path));
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function fullPath($path) {
		return str_replace("//", "/", "{$this->path}/$path");
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function getPathPrefix($guid) {
		return "/$guid/";
	}
}