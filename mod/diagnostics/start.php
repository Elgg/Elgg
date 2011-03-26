<?php
/**
 * Elgg diagnostics
 *
 * @package ElggDiagnostics
 */

elgg_register_event_handler('init', 'system', 'diagnostics_init');

/**
 * Initialise the diagnostics tool
 */
function diagnostics_init() {

	// Register a page handler, so we can have nice URLs
	elgg_register_page_handler('diagnostics','diagnostics_page_handler');

	// Add admin menu item
	elgg_register_admin_menu_item('develop', 'diagnostics', 'utilities');

	// Register some actions
	$file = elgg_get_plugins_path() . "diagnostics/actions/download.php";
	elgg_register_action("diagnostics/download", $file, 'admin');
}

/**
 * Generate a basic report.
 *
 * @return string
 */
function diagnostics_basic_hook($hook, $entity_type, $returnvalue, $params) {

	// Get version information
	$version = get_version();
	$release = get_version(true);

	$returnvalue .= elgg_echo('diagnostics:report:basic', array($release, $version));

	return $returnvalue;
}

/**
 * Get some information about the plugins installed on the system.
 *
 * @return tring
 */
function diagnostics_plugins_hook($hook, $entity_type, $returnvalue, $params) {
	// @todo this is a really bad idea because of the new plugin system
	//$returnvalue .= elgg_echo('diagnostics:report:plugins', array(print_r(elgg_get_plugins(), true)));

	return $returnvalue;
}

/**
 * Recursively list through a directory tree producing a hash of all installed files
 *
 * @param starting dir $dir
 * @param buffer $buffer
 */
function diagnostics_md5_dir($dir) {
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
 * @return string
 */
function diagnostics_sigs_hook($hook, $entity_type, $returnvalue, $params) {

	$base_dir = elgg_get_root_path();
	$returnvalue .= elgg_echo('diagnostics:report:md5', array(diagnostics_md5_dir($base_dir)));

	return $returnvalue;
}

/**
 * Get some information about the php install
 *
 * @return string
 */
function diagnostics_phpinfo_hook($hook, $entity_type, $returnvalue, $params) {

	ob_start();
	phpinfo();
	$phpinfo = array('phpinfo' => array());

	if (preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER)) {

		foreach ($matches as $match) {
			if (strlen($match[1])) {
				$phpinfo[$match[1]] = array();
			} else if(isset($match[3])) {
				$phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
			} else {
				$phpinfo[end(array_keys($phpinfo))][] = $match[2];
			}
		}
	}

	$returnvalue .= elgg_echo('diagnostics:report:php', array(print_r($phpinfo, true)));

	return $returnvalue;
}

/**
 * Get global variables.
 *
 * @return string
 */
function diagnostics_globals_hook($hook, $entity_type, $returnvalue, $params) {
	global $CONFIG;

	$output = str_replace($CONFIG->dbpass, '<<DBPASS>>', print_r($GLOBALS, true));
	$returnvalue .= elgg_echo('diagnostics:report:globals', array($output));

	return $returnvalue;
}

elgg_register_plugin_hook_handler("diagnostics:report", "system", "diagnostics_basic_hook", 0); // show basics first
elgg_register_plugin_hook_handler("diagnostics:report", "system", "diagnostics_plugins_hook", 2); // Now the plugins
elgg_register_plugin_hook_handler("diagnostics:report", "system", "diagnostics_sigs_hook", 1); // Now the signatures

elgg_register_plugin_hook_handler("diagnostics:report", "system", "diagnostics_globals_hook"); // Global variables
elgg_register_plugin_hook_handler("diagnostics:report", "system", "diagnostics_phpinfo_hook"); // PHP info
