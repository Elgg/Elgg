<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 * 
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
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
