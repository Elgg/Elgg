<?php
namespace Elgg\Filesystem\Adapter;

interface Adapter {
	public function __construct(array $config);
	
	public function file($name);
	
	public function directory($path);
	
	public static function getPathPrefix($guid);
}