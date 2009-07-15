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

		/**
		 * UTF safe str_split.
		 * This is only used here since we don't have access to the file store code.
		 * TODO: This is a horrible hack, so clean this up!
		 */
		function __id_mb_str_split($string, $charset = 'UTF8')
		{
			if (is_callable('mb_substr'))
			{
				$length = mb_strlen($string);
				$array = array();
				
				while ($length)
				{
					$array[] = mb_substr($string, 0, 1, $charset);
					$string = mb_substr($string, 1, $length, $charset);
					
					$length = mb_strlen($string);
				}
				
				return $array;
			}
			else
				return str_split($string);
			
			return false;
		}
		
		global $CONFIG;
			
		$contents = '';
		
		if ($mysql_dblink = @mysql_connect($CONFIG->dbhost,$CONFIG->dbuser,$CONFIG->dbpass, true)) {

			
			$username = $_GET['username'];
			//$username = preg_replace('/[^A-Za-z0-9\_\-]/i','',$username);
			$blacklist = '/[' .
			'\x{0080}-\x{009f}' . # iso-8859-1 control chars
			'\x{00a0}' .          # non-breaking space
			'\x{2000}-\x{200f}' . # various whitespace
			'\x{2028}-\x{202f}' . # breaks and control chars
			'\x{3000}' .          # ideographic space
			'\x{e000}-\x{f8ff}' . # private use
			']/u';
			if (
				preg_match($blacklist, $username) ||	

				(strpos($username, '/')!==false) ||
				(strpos($username, '\\')!==false) ||
				(strpos($username, '"')!==false) ||
				(strpos($username, '\'')!==false) ||
				(strpos($username, '*')!==false) ||
				(strpos($username, '&')!==false) ||
				(strpos($username, ' ')!==false)
			) exit;
			
			$userarray = __id_mb_str_split($username);
				
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
			if (@mysql_select_db($CONFIG->dbname,$mysql_dblink)) {
				// get dataroot and simplecache_enabled in one select for efficiency
				if ($result = mysql_query("select name, value from {$CONFIG->dbprefix}datalists where name in ('dataroot','simplecache_enabled')",$mysql_dblink)) {
					$simplecache_enabled = true;
					$row = mysql_fetch_object($result);
					while ($row) {
						if ($row->name == 'dataroot') {
							$dataroot = $row->value;
						} else if ($row->name == 'simplecache_enabled') {
							$simplecache_enabled = $row->value;
						}
						$row = mysql_fetch_object($result);
					}
				}
			}
		}
		if ($simplecache_enabled) {
			$filename = $dataroot . $matrix . "{$username}/profile/" . $username . $size . ".jpg";
			$contents = @file_get_contents($filename);
			if (empty($contents)) {			
				global $viewinput;
				$viewinput['view'] = 'icon/user/default/'.$size;
				ob_start();
				include(dirname(dirname(dirname(__FILE__))).'/simplecache/view.php');
				$loc = ob_get_clean();
				header('Location: ' . $loc);
				exit;
				//$contents = @file_get_contents(dirname(__FILE__) . "/graphics/default{$size}.jpg");
			}	else {		
				header("Content-type: image/jpeg");
				header('Expires: ' . date('r',time() + 864000));
				header("Pragma: public");
				header("Cache-Control: public");
				header("Content-Length: " . strlen($contents));
				$splitString = str_split($contents, 1024);
				foreach($splitString as $chunk)
					echo $chunk;
			}
		} else {
				mysql_close($mysql_dblink);
				require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
				set_input('username',$username);
				set_input('size',$size);
				require_once(dirname(__FILE__).'/icon.php');
		}
?>
