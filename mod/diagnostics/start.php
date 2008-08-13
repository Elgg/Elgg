<?php
	/**
	 * Elgg diagnostics
	 * 
	 * @package ElggDiagnostics
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
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
		}
	}
	
	/**
	 * Diagnostics page.
	 *
	 * @param array $page Array of page elements, forwarded by the page handling mechanism
	 */
	function diagnostics_page_handler($page) 
	{
		global $CONFIG;
		
		// only interested in one page for now
		include($CONFIG->pluginspath . "diagnostics/index.php"); 
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
		
		include_once($CONFIG->path . "version.php");
		
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
	 * Get some information about the current session
	 *
	 * @param unknown_type $hook
	 * @param unknown_type $entity_type
	 * @param unknown_type $returnvalue
	 * @param unknown_type $params
	 */
	function diagnostics_session_hook($hook, $entity_type, $returnvalue, $params)
	{
		global $CONFIG;
		
		$returnvalue .= sprintf(elgg_echo('diagnostics:report:session'), print_r($_SESSION, true));
		
		return $returnvalue;
	}
	
	// Initialise log browser
	register_elgg_event_handler('init','system','diagnostics_init');
	register_elgg_event_handler('pagesetup','system','diagnostics_pagesetup');
	
	register_plugin_hook("diagnostics:report", "all", "diagnostics_basic_hook", 0); // show basics first
	register_plugin_hook("diagnostics:report", "all", "diagnostics_plugins_hook", 2); // Now the plugins
	register_plugin_hook("diagnostics:report", "all", "diagnostics_sigs_hook", 1); // Now the signatures
	
	register_plugin_hook("diagnostics:report", "all", "diagnostics_session_hook"); // Session
?>