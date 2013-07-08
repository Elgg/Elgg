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
interface Elgg_Database_Connection {

	/**
	 * Connect
	 *
	 * @return Elgg_Database_Connection
	 */
	public function connect();

	/**
	 * Is connected
	 *
	 * @return bool
	 */
	public function isConnected();

	/**
	 * Disconnect
	 *
	 * @return Elgg_Database_Connection
	 */
	public function disconnect();

	/**
	 * Execute
	 *
	 * @param string $sql SQL statement
	 * @return Elgg_Database_Result
	 */
	public function execute($sql);

	/**
	 * Get last generated id
	 *
	 * @return int
	 */
	public function getLastGeneratedValue();
}
