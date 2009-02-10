<?php

	/**
	 * Elgg profile icon
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Get DB settings, connect
		require_once(dirname(dirname(dirname(__FILE__))). '/engine/settings.php');
		
		global $CONFIG;
		
		$contents = '';
		
		if ($dblink = @mysql_connect($CONFIG->dbhost,$CONFIG->dbuser,$CONFIG->dbpass)) {

			
			$username = $_GET['username'];
			$username = preg_replace('/[^A-Za-z0-9\_\-]/i','',$username);
			$userarray = str_split($username);
				
			$matrix = '';
			$length = 5;
			if (sizeof($userarray) < $length) $length = sizeof($userarray);
			for ($n = 0; $n < $length; $n++) {
				$matrix .= $userarray[$n] . "/";
			}	
			
		// Get the size
			$size = strtolower($_GET['size']);
			if (!in_array($size,array('large','medium','small','tiny','master','topbar')))
				$size = "medium";
			
		// Try and get the icon
			if (@mysql_select_db($CONFIG->dbname,$dblink)) {
				if ($result = mysql_query("select value from {$CONFIG->dbprefix}datalists where name = 'dataroot'",$dblink)) {
					$row = mysql_fetch_object($result);
					$dataroot = $row->value;
				}
				$filename = $dataroot . $matrix . "{$username}/profile/" . $username . $size . ".jpg";
				$contents = @file_get_contents($filename);
			}
		}
		if (empty($contents)) {
			
			global $CONFIG;
			$contents = @file_get_contents(dirname(__FILE__) . "/graphics/default{$size}.jpg");
			
		}
		
		header("Content-type: image/jpeg");
		header('Expires: ' . date('r',time() + 864000));
		header("Pragma: public");
		header("Cache-Control: public");
		header("Content-Length: " . strlen($contents));
		echo $contents;

?>