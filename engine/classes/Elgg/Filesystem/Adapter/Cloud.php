<?php
use Gaufrette\Filesystem;

namespace Elgg\Filesystem\Adapter;


interface Cloud {
	/**
	 * 
	 * @param array $params A set of params that can re-instantiate a Filesystem
	 */
	public function __construct(array $params);
	
	/**
	 * Get the filesystem to work
	 * 
	 * @return Filesystem
	 */
	public function getFilesystem();
	
	/**
	 * Returns a set of parameters that can be passed back into a coud
	 * adapater to re-instantiate an identical connection.
	 * 
	 * @return array
	 */
	public function getParameters();
}
