<?php
/**
 * Elgg Social
 * Functions and objects which provide powerful social aspects within Elgg
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider
 * @link http://elgg.org/

/**
  * Filters a string into an array of significant words
  *
  * @param string $string
  * @return array
  */
function filter_string($string) {
	// Convert it to lower and trim
	$string = strtolower($string);
	$string = trim($string);

	// Remove links and email addresses
	// match protocol://address/path/file.extension?some=variable&another=asf%
	$string = preg_replace("/\s([a-zA-Z]+:\/\/[a-z][a-z0-9\_\.\-]*[a-z]{2,6}[a-zA-Z0-9\/\*\-\?\&\%\=]*)([\s|\.|\,])/iu"," ", $string);
	// match www.something.domain/path/file.extension?some=variable&another=asf%
	$string = preg_replace("/\s(www\.[a-z][a-z0-9\_\.\-]*[a-z]{2,6}[a-zA-Z0-9\/\*\-\?\&\%\=]*)([\s|\.|\,])/iu"," ", $string);
	// match name@address
	$string = preg_replace("/\s([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})([\s|\.|\,])/iu"," ", $string);

	// Sanitise the string; remove unwanted characters
	$string = preg_replace('/\W/ui', ' ', $string);

	// Explode it into an array
	$terms = explode(' ',$string);

	// Remove any blacklist terms
	//$terms = array_filter($terms, 'remove_blacklist');

	return $terms;
}

/**
 * Returns true if the word in $input is considered significant
 *
 * @param string $input
 * @return true|false
 */
function remove_blacklist($input) {
	global $CONFIG;

	if (!is_array($CONFIG->wordblacklist)) {
		return $input;
	}

	if (strlen($input) < 3 || in_array($input,$CONFIG->wordblacklist)) {
		return false;
	}

	return true;
}


/**
 * Initialise.
 *
 * Sets a blacklist of words in the current language. This is a comma separated list in word:blacklist.
 */
function social_init() {
	global $CONFIG;

	$CONFIG->wordblacklist = array();

	$list = explode(',', elgg_echo('word:blacklist'));
	if ($list) {
		foreach ($list as $l) {
			$CONFIG->wordblacklist[] = trim($l);
		}
	} else {
		// Fallback - shouldn't happen
		$CONFIG->wordblacklist = array(
			'and',
			'the',
			'then',
			'but',
			'she',
			'his',
			'her',
			'him',
			'one',
			'not',
			'also',
			'about',
			'now',
			'hence',
			'however',
			'still',
			'likewise',
			'otherwise',
			'therefore',
			'conversely',
			'rather',
			'consequently',
			'furthermore',
			'nevertheless',
			'instead',
			'meanwhile',
			'accordingly',
			'this',
			'seems',
			'what',
			'whom',
			'whose',
			'whoever',
			'whomever',
		);
	}
}

register_elgg_event_handler("init","system","social_init");