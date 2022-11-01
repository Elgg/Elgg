<?php

namespace Elgg\Diagnostics;

use Elgg\Project\Paths;

/**
 * Event handlers for Developers plugin
 */
class Reports {

	/**
	 * Generate a basic report
	 *
	 * @param \Elgg\Event $event 'diagnostics:report', 'system'
	 *
	 * @return string
	 */
	public static function getBasic(\Elgg\Event $event) {
		return $event->getValue() . elgg_echo('diagnostics:report:basic', [elgg_get_release()]);
	}
	
	/**
	 * Recursively list through a directory tree producing a hash of all installed files
	 *
	 * @param string $dir starting dir
	 *
	 * @return string
	 */
	protected static function md5dir($dir) {
		$dir = Paths::sanitize($dir);
		$extensions_allowed = ['php', 'js', 'css'];
		
		if (!is_dir($dir)) {
			return '';
		}
		
		$buffer = '';
	
		$dh = new \DirectoryIterator($dir);
		foreach ($dh as $fileinfo) {
			if ($fileinfo->isDot()) {
				continue;
			}
			
			if ($fileinfo->isDir()) {
				$buffer .= self::md5dir($fileinfo->getPathname());
			}
			
			if (!in_array($fileinfo->getExtension(), $extensions_allowed)) {
				continue;
			}
			
			$filename = Paths::sanitize($fileinfo->getPathname(), false);
			$buffer .= md5_file($filename) . " {$filename}" . PHP_EOL;
		}
	
		return $buffer;
	}
	
	/**
	 * Get some information about the files installed on a system
	 *
	 * @param \Elgg\Event $event 'diagnostics:report', 'system'
	 *
	 * @return string
	 */
	public static function getSigs(\Elgg\Event $event) {
		return $event->getValue() . elgg_echo('diagnostics:report:md5', [self::md5dir(elgg_get_root_path())]);
	}
	
	/**
	 * Get some information about the php install
	 *
	 * @param \Elgg\Event $event 'diagnostics:report', 'system'
	 *
	 * @return string
	 */
	public static function getPHPInfo(\Elgg\Event $event) {
	
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
	
		return $event->getValue() . elgg_echo('diagnostics:report:php', [print_r($phpinfo, true)]);
	}
	
	/**
	 * Get global variables
	 *
	 * @param \Elgg\Event $event 'diagnostics:report', 'system'
	 *
	 * @return string
	 */
	public static function getGlobals(\Elgg\Event $event) {
	
		$output = str_replace(elgg_get_config('dbpass'), '<<DBPASS>>', print_r($GLOBALS, true));
		return $event->getValue() . elgg_echo('diagnostics:report:globals', [$output]);
	}
}
