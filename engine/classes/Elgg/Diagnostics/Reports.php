<?php

namespace Elgg\Diagnostics;

/**
 * Plugin hook handlers for Developers plugin
 */
class Reports {

	/**
	 * Generate a basic report
	 *
	 * @param \Elgg\Hook $hook 'diagnostics:report', 'system'
	 *
	 * @return string
	 */
	public static function getBasic(\Elgg\Hook $hook) {
	
		// Get version information
		$version = elgg_get_version();
		$release = elgg_get_version(true);
	
		return $hook->getValue() . elgg_echo('diagnostics:report:basic', [$release, $version]);
	}
	
	/**
	 * Recursively list through a directory tree producing a hash of all installed files
	 *
	 * @param string $dir starting dir
	 *
	 * @return string
	 */
	protected static function md5dir($dir) {
		$extensions_allowed = ['.php', '.js', '.css'];
	
		$buffer = '';
	
		if (in_array(strrchr(trim($dir, "/"), '.'), $extensions_allowed)) {
			$dir = rtrim($dir, "/");
			$buffer .= md5_file($dir). "  " . $dir . "\n";
		} else if (is_dir($dir)) {
			$handle = opendir($dir);
			while ($file = readdir($handle)) {
				if (($file != '.') && ($file != '..')) {
					$buffer .= self::md5dir($dir . $file. "/");
				}
			}
	
			closedir($handle);
		}
	
		return $buffer;
	}
	
	/**
	 * Get some information about the files installed on a system
	 *
	 * @param \Elgg\Hook $hook 'diagnostics:report', 'system'
	 *
	 * @return string
	 */
	public static function getSigs(\Elgg\Hook $hook) {
		return $hook->getValue() . elgg_echo('diagnostics:report:md5', [self::md5dir(elgg_get_root_path())]);
	}
	
	/**
	 * Get some information about the php install
	 *
	 * @param \Elgg\Hook $hook 'diagnostics:report', 'system'
	 *
	 * @return string
	 */
	public static function getPHPInfo(\Elgg\Hook $hook) {
	
		ob_start();
		phpinfo();
		$phpinfo = ['phpinfo' => []];
	
		$matches = [];
		if (preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				if (elgg_strlen($match[1])) {
					$phpinfo[$match[1]] = [];
				} else if (isset($match[3])) {
					$phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? [$match[3], $match[4]] : $match[3];
				} else {
					$phpinfo[end(array_keys($phpinfo))][] = $match[2];
				}
			}
		}
	
		return $hook->getValue() . elgg_echo('diagnostics:report:php', [print_r($phpinfo, true)]);
	}
	
	/**
	 * Get global variables
	 *
	 * @param \Elgg\Hook $hook 'diagnostics:report', 'system'
	 *
	 * @return string
	 */
	public static function getGlobals(\Elgg\Hook $hook) {
	
		$output = str_replace(elgg_get_config('dbpass'), '<<DBPASS>>', print_r($GLOBALS, true));
		return $hook->getValue() . elgg_echo('diagnostics:report:globals', [$output]);
	}
}
