<?php
/**
 * Original Elgg Database Driver
 */
class Elgg_Database_Driver_Default {
	/**
	 * Establish a connection to the database servser
	 *
	 * Connect to the database server and use the Elgg database for a particular database link
	 *
	 * @param string $dblinkname The type of database connection. Used to identify the
	 * resource. eg "read", "write", or "readwrite".
	 *
	 * @return void
	 * @access private
	 */
	public function establishLink($dblinkname = "readwrite") {
		//TODO move it back to ElggDatabase, leave only mysql_connect and mysql_select_db here + link creation
		
		// Get configuration, and globalise database link
		global $CONFIG, $dblink, $DB_QUERY_CACHE, $dbcalls;
	
		if ($dblinkname != "readwrite" && isset($CONFIG->db[$dblinkname])) {
			if (is_array($CONFIG->db[$dblinkname])) {
				$index = rand(0, sizeof($CONFIG->db[$dblinkname]));
				$dbhost = $CONFIG->db[$dblinkname][$index]->dbhost;
				$dbuser = $CONFIG->db[$dblinkname][$index]->dbuser;
				$dbpass = $CONFIG->db[$dblinkname][$index]->dbpass;
				$dbname = $CONFIG->db[$dblinkname][$index]->dbname;
			} else {
				$dbhost = $CONFIG->db[$dblinkname]->dbhost;
				$dbuser = $CONFIG->db[$dblinkname]->dbuser;
				$dbpass = $CONFIG->db[$dblinkname]->dbpass;
				$dbname = $CONFIG->db[$dblinkname]->dbname;
			}
		} else {
			$dbhost = $CONFIG->dbhost;
			$dbuser = $CONFIG->dbuser;
			$dbpass = $CONFIG->dbpass;
			$dbname = $CONFIG->dbname;
		}
	
		// Connect to database
		if (!$link = mysql_connect($dbhost, $dbuser, $dbpass, true)) {
			$msg = elgg_echo('DatabaseException:WrongCredentials',
					array($dbuser, $dbhost, "****"));
			throw new DatabaseException($msg);
		}
		$dblink[$dblinkname] = new Elgg_Database_Driver_Default_Connection($link);
	
		if (!mysql_select_db($dbname, $link)) {
			$msg = elgg_echo('DatabaseException:NoConnect', array($dbname));
			throw new DatabaseException($msg);
		}
	
		// Set DB for UTF8
		mysql_query("SET NAMES utf8");
	}
	
	/**
	 * Returns (if required, also creates) a database link resource.
	 *
	 * Database link resources are stored in the {@link $dblink} global.  These
	 * resources are created by {@link setup_db_connections()}, which is called if
	 * no links exist.
	 *
	 * @param string $dblinktype The type of link we want: "read", "write" or "readwrite".
	 *
	 * @return Elgg_Database_Connection Database link
	 * @access private
	 */
	function getLink($dblinktype) {
		//TODO move it back to ElggDatabase
		global $dblink;
	
		if (isset($dblink[$dblinktype])) {
			return $dblink[$dblinktype];
		} else if (isset($dblink['readwrite'])) {
			return $dblink['readwrite'];
		} else {
			$this->setupConnections();
			return $this->getLink($dblinktype);
		}
	}
}