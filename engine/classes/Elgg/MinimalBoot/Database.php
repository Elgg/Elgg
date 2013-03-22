<?php

/**
 * Minimal MySQL fetch wrapper
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 */
class Elgg_MinimalBoot_Database {

	protected $link;

	/**
	 * Constructor
	 *
	 * @param stdClass $config
	 * @throws DatabaseException
	 */
	public function __construct(stdClass $config) {
		$this->link = @mysql_connect($config->dbhost, $config->dbuser, $config->dbpass, true);
		if (!$this->link) {
			throw new DatabaseException('Failed to connect to database.');
		}
		if (!mysql_select_db($config->dbname, $this->link)) {
			throw new DatabaseException('Failed to select database.');
		}
		// Set DB for UTF8
		mysql_query("SET NAMES utf8", $this->link);
	}

	/**
	 * Quote a string for use in a query
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public function quote($string) {
		return "'" . mysql_real_escape_string($string, $this->link) . "'";
	}

	/**
	 * Execute a fetch query returning an array of row objects
	 *
	 * @param string $query
	 *
	 * @return stdClass[]
	 * @throws DatabaseException
	 */
	public function getData($query) {
		if (!is_resource($this->link)) {
			throw new DatabaseException("Connection to database was lost.");
		}

		$result = mysql_query($query, $this->link);

		if (mysql_errno($this->link)) {
			throw new DatabaseException(mysql_error($this->link) . "\n\n QUERY: " . $query);
		}

		$return = array();
		while ($row = mysql_fetch_object($result)) {
			$return[] = $row;
		}

		return $return;
	}

	/**
	 * Close the DB connection
	 */
	public function close() {
		if (is_resource($this->link)) {
			mysql_close($this->link);
		}
	}

	public function __destruct() {
		$this->close();
	}
}
