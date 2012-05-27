<?php
/**
 *  Copyright (C) 2011-2012 Quanbit Software S.A.
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 */

/**
 * This class is used to read a config (.ini) file settings.
 * 
 * @author andres
 */
class ElggTestConfigFile
{
	/**
	 * The filename we should read the settings from
	 * 
	 * @var string
	 */
	protected $filename;
	
	/**
	 * Initialize the object with the filenames it should use to read
	 * the settings.
	 * 
	 * @param string $filename
	 */
	public function __construct($filename)
	{
		$this->filename = $filename;
	}
	
	/**
	 * Read the file settings and return an associative array with
	 * the file mappings. Try to read the settings from $this->filename
	 * 
	 * @throws Exception
	 * @return array
	 */
	public function getMappings()
	{
		$mappings = false;
		if (file_exists($this->filename)) 
		{
			$mappings = parse_ini_file($this->filename);
		}
		
		if ($mappings === false)
		{
			$msg = sprintf("Coulnd't read settings from %s", $this->filename);
			throw new Exception($msg);
		}
		return $mappings;
	}
	
	public function getMappingsAs($keys, $replacements)
	{
		$mappings = $this->getMappings();
		$result = array();
		foreach ($keys as $index => $key) 
		{
			$newKey = $replacements[$index];
			$result[$newKey] = $mappings[$key];
		}
		return $result;
	}
	
}