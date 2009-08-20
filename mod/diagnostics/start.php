<?php
	/**
	 * Elgg diagnostics
	 * 
	 * @package ElggDiagnostics
	 * @author Curverider Ltd
	 * @link http://elgg.com/
	 */

	/**
	 * Initialise the diagnostics tool
	 *
	 */
	function diagnostics_init()
	{
		global $CONFIG;
		
		// Register a page handler, so we can have nice URLs
		register_page_handler('diagnostics','diagnostics_page_handler');
		
		// Register some actions
		register_action("diagnostics/download",false, $CONFIG->pluginspath . "diagnostics/actions/download.php");
		
		// If debug on then enable an example test
		if ((isset($CONFIG->debug)) && ($CONFIG->debug))
		{
			register_elgg_test(elgg_echo('diagnostics:unittest:example'), 'diagnostics_test_test');	
		}
	}
	
	/**
	 * Adding the diagnostics to the admin menu
	 *
	 */
	function diagnostics_pagesetup()
	{
		if (get_context() == 'admin' && isadminloggedin()) {
			global $CONFIG;
			add_submenu_item(elgg_echo('diagnostics'), $CONFIG->wwwroot . 'pg/diagnostics/');
			add_submenu_item(elgg_echo('diagnostics:unittester'), $CONFIG->wwwroot . 'pg/diagnostics/tests/');
		}
	}
	
	function diagnostics_test_test()
	{
		ob_start();

		for ($n = 0; $n<5; $n++)
			echo "$n ";
		
		return ElggTestResult::CreateSuccessResult(ob_get_clean());
	}
	
	/**
	 * Diagnostics page.
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
	function diagnostics_page_handler($page) 
	{
		global $CONFIG;
		
		if (isset($page[0]))
		{
			switch ($page[0])
			{
				case 'tests' : 	
					if ((isset($page[1])) && ($page[1])) {
						switch ($page[1]) 
						{
							case 'all': break; 
							default: set_input('test_func', $page[1]);
						}
						
						include($CONFIG->pluginspath . "diagnostics/testreport.php"); 
					}
					else
						include($CONFIG->pluginspath . "diagnostics/unittester.php"); 
				break;
				default: include($CONFIG->pluginspath . "diagnostics/index.php");  	
			}
		}
		else
		{
			// only interested in one page for now
			include($CONFIG->pluginspath . "diagnostics/index.php");
		} 
	}
	
	/**
	 * Generate a basic report.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function diagnostics_basic_hook($hook, $entity_type, $returnvalue, $params)
	{
		global $CONFIG;
		
		// Get version information
		$version = get_version();
		$release = get_version(true);
		
		$returnvalue .= sprintf(elgg_echo('diagnostics:report:basic'), $release, $version);
		
		return $returnvalue;
	}
	
	/**
	 * Get some information about the plugins installed on the system.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function diagnostics_plugins_hook($hook, $entity_type, $returnvalue, $params)
	{
		$returnvalue .= sprintf(elgg_echo('diagnostics:report:plugins'), print_r(get_installed_plugins(), true));
		
		return $returnvalue;
	}
	
	/**
	 * Recursively list through a directory tree producing a hash of all installed files
	 *
	 * @param starting dir $dir
	 * @param buffer $buffer
	 */
	function diagnostics_md5_dir($dir)
	{
		//if (is_file(trim($dir, "/"))) {
		$extensions_allowed = array('.php', '.gif', '.png', '.jpg');
		
		$buffer = "";
		
		if (in_array(strrchr(trim($dir, "/"), '.'), $extensions_allowed))
		{
			//$dir = trim($dir, "/");
			$buffer .= md5_file($dir). "  " . trim($dir, "/") . "\n";
		} else if ($handle = opendir($dir)) {
			while ($file = readdir($handle)) {
				
				if (($file != '.') && ($file != '..')) {
					$buffer .= diagnostics_md5_dir($dir . $file. "/", $buffer);
				}
			}
		}
		
		return $buffer;
	}
	
	/**
	 * Get some information about the files installed on a system.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function diagnostics_sigs_hook($hook, $entity_type, $returnvalue, $params)
	{
		global $CONFIG;
		
		$returnvalue .= sprintf(elgg_echo('diagnostics:report:md5'), diagnostics_md5_dir($CONFIG->path));
		
		return $returnvalue;
	}
	
	/**
	 * Get some information about the php install
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function diagnostics_phpinfo_hook($hook, $entity_type, $returnvalue, $params)
	{
		global $CONFIG;
		
		ob_start();
		phpinfo();
		$phpinfo = array('phpinfo' => array());
		
		if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
    
		foreach($matches as $match)
		{
       		if(strlen($match[1]))
            	$phpinfo[$match[1]] = array();
        	else if(isset($match[3]))
            	$phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
        	else
            	$phpinfo[end(array_keys($phpinfo))][] = $match[2];
		}
		
		
		$returnvalue .= sprintf(elgg_echo('diagnostics:report:php'), print_r($phpinfo, true));
		
		return $returnvalue;
	}
	
	/**
	 * Get global variables.
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 * @return unknown
	 */
	function diagnostics_globals_hook($hook, $entity_type, $returnvalue, $params)
	{
		global $CONFIG;
		
		$returnvalue .= sprintf(elgg_echo('diagnostics:report:globals'), print_r($GLOBALS, true));
		
		return $returnvalue;
	}
	
	// Initialise log browser
	register_elgg_event_handler('init','system','diagnostics_init');
	register_elgg_event_handler('pagesetup','system','diagnostics_pagesetup');
	
	register_plugin_hook("diagnostics:report", "all", "diagnostics_basic_hook", 0); // show basics first
	register_plugin_hook("diagnostics:report", "all", "diagnostics_plugins_hook", 2); // Now the plugins
	register_plugin_hook("diagnostics:report", "all", "diagnostics_sigs_hook", 1); // Now the signatures
	
	register_plugin_hook("diagnostics:report", "all", "diagnostics_globals_hook"); // Global variables
	register_plugin_hook("diagnostics:report", "all", "diagnostics_phpinfo_hook"); // PHP info
?>