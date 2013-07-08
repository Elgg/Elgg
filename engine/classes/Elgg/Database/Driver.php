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
interface Elgg_Database_Driver {

	/**
	 * Check environment
	 *
	 * @return bool
	 */
	public function checkEnvironment();

	/**
	 * Get connection
	 *
	 * @return Elgg_Database_Connection
	 */
	public function getConnection();

	/**
	 * Create result
	 *
	 * @param resource $resource A resource
	 * @return Elgg_Database_Result
	 */
	public function createResult($resource);

	/**
	 * Get last generated value
	 *
	 * @return int
	 */
	public function getLastGeneratedValue();
}
