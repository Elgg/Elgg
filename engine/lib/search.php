<?php

/**
 * Functions for Elgg's search system.
 * Contains functions and default plugin hook callbacks for common search types.
 * 
 * @package Elgg.Core
 * @subpackage Search
 */

/**
 * Initialize the file library.
 * Listens to system init and configures the search system
 *
 * @return void
 * @access private
 */
function _elgg_search_init() {

	global $CONFIG;
	
	$CONFIG->search_info['min_chars'] = $CONFIG->ft_min_word_len;
	$CONFIG->search_info['max_chars'] = $CONFIG->ft_max_word_len;
	
	error_log(print_r($CONFIG->search_info, true));
	
}

/**
 * Checks if minimum and maximum lengths of words for MySQL search are defined and store them in Elgg data lists
 * 
 * @return void
 * @access private
 */
function _elgg_search_upgrade() {
	
	$result = false;
	try {
		$result = get_data_row('SELECT @@ft_min_word_len as min, @@ft_max_word_len as max');
	} catch (DatabaseException $e) {
		// some servers don't have these values set which leads to exception
		elgg_log($e->getMessage(), 'ERROR');
	}
	
	if ($result) {
		$min = $result->min;
		$max= $result->max;
	} else {
		// defaults from MySQL on Ubuntu Linux
		$min = 4;
		$max = 90;
	}
	
	elgg_save_config('ft_min_word_len', $min);
	elgg_save_config('ft_max_word_len', $max);
	
}

elgg_register_event_handler('init', 'system', '_elgg_search_init');
elgg_register_event_handler('upgrade', 'system', '_elgg_search_upgrade');
