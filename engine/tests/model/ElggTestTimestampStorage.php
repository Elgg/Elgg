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
 * This class is used to share a timestamp across tests
 * that run in separate processes. Since the timestamp is
 * being modified by the different tests, we need a way of
 * inter-process communication and a temp file seems like a 
 * nice sollution.
 * 
 * @author andres
 */

class ElggTestTimestampStorage
{
	private $handle;
	private $filename;
	
	/**
	 * A simple utility method to read the timestamp
	 * stored in the file.
	 * 
	 * @return DateTime
	 */
	public static function readTimestampFromFile()
	{
		$storage = new ElggTestTimestampStorage();
		$milestone = $storage->getTimestamp();
		$storage->close();
		return $milestone;
	}
	
	/**
	 * A simple utility method to write a timestamp to a storage file.
	 * 
	 *  @param DateTime $timestamp
	 */
	public static function writeTimestampToFile($timestamp)
	{
		$storage = new ElggTestTimestampStorage();
		$storage->truncate();
    	$storage->setTimestamp($timestamp);
    	$storage->close();
	}
	
	/**
	 * Initialize the strorage file.
	 * Uses the sytem temporary directory to create a filename.
	 */
	public function __construct()
	{
		$this->filename = sys_get_temp_dir() .  '/ElggLastTestStartTimestamp';
		$this->handle = fopen($this->filename, 'c+');
	}
	
	/**
	 * Set the timestamp in the file in a human readable format.
	 * 
	 * @param DateTime $timestamp
	 */
	public function setTimestamp($timestamp)
	{
		fwrite($this->handle, $timestamp->format('Y-m-d H:i:s'));
	}
	
	/**
	 * Retrieve the timestamp stored in the file.
	 * Only one timestamp is assumed to exist.
	 * 
	 * @return DateTime
	 */
	public function getTimestamp()
	{
		if (!$this->hasTimestamp())
		{
			throw new Exception("There is no timestamp available");
		}
		rewind($this->handle);
		$text = stream_get_contents($this->handle);
		return new DateTime($text);
	}
	
	/**
	 * Check if there is a timestamp stored in the file.
	 * 
	 * @return boolean
	 */
	public function hasTimestamp()
	{
		rewind($this->handle);
		$text = stream_get_contents($this->handle);
		return trim($text) != '';
	}	
		
	/**
	 * Close the file.
	 */
	public function close()
	{
		fclose($this->handle);
	}
	
	/**
	 * Remove all data from the file.
	 */
	public function truncate()
	{
		ftruncate($this->handle, 0);
	}
}