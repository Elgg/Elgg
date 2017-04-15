<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 * 
 * Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * http://framework.zend.com/license/new-bsd New BSD License
 *
 * @package    Elgg.Core
 * @subpackage Database
 * @since      1.9.0
 */
interface Elgg_Database_Result extends
	Countable,
	Iterator
{

	/**
	 * Get affected rows
	 *
	 * @return int
	 */
	public function getAffectedRows();

	/**
	 * Get generated value
	 *
	 * @return int
	 */
	public function getGeneratedValue();

	/**
	 * Get the resource
	 *
	 * @return resource
	 */
	public function getResource();

}
