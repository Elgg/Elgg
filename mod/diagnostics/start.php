<?php
/**
 * Elgg diagnostics
 *
 * @package ElggDiagnostics
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
	if (elgg_get_context() == 'admin' && isadminloggedin()) {
		global $CONFIG;
		add_submenu_item(elgg_echo('diagnostics'), 'pg/diagnostics/');
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

	// Get version information
	$version = get_version();
	$release = get_version(true);

	$returnvalue .= elgg_echo('diagnostics:report:basic', array($release, $version));

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
	$returnvalue .= elgg_echo('diagnostics:report:plugins', array(print_r(get_installed_plugins(), true)));

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
	$extensions_allowed = array('.php', '.js', '.css');

	$buffer = "";

	if (in_array(strrchr(trim($dir, "/"), '.'), $extensions_allowed)) {
		$dir = rtrim($dir, "/");
		$buffer .= md5_file($dir). "  " . $dir . "\n";
	} else if (is_dir($dir)) {
		$handle = opendir($dir);
		while ($file = readdir($handle)) {
			if (($file != '.') && ($file != '..')) {
				$buffer .= diagnostics_md5_dir($dir . $file. "/", $buffer);
			}
		}

		closedir($handle);
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

	$returnvalue .= elgg_echo('diagnostics:report:md5', array(diagnostics_md5_dir($CONFIG->path)));

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


	$returnvalue .= elgg_echo('diagnostics:report:php', array(print_r($phpinfo, true)));

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

	$output = str_replace($CONFIG->dbpass, '<<DBPASS>>', print_r($GLOBALS, true));
	$returnvalue .= elgg_echo('diagnostics:report:globals', array($output));

	return $returnvalue;
}

// Initialise log browser
elgg_register_event_handler('init','system','diagnostics_init');
elgg_register_event_handler('pagesetup','system','diagnostics_pagesetup');

elgg_register_plugin_hook_handler("diagnostics:report", "system", "diagnostics_basic_hook", 0); // show basics first
elgg_register_plugin_hook_handler("diagnostics:report", "system", "diagnostics_plugins_hook", 2); // Now the plugins
elgg_register_plugin_hook_handler("diagnostics:report", "system", "diagnostics_sigs_hook", 1); // Now the signatures

elgg_register_plugin_hook_handler("diagnostics:report", "system", "diagnostics_globals_hook"); // Global variables
elgg_register_plugin_hook_handler("diagnostics:report", "system", "diagnostics_phpinfo_hook"); // PHP info
?>