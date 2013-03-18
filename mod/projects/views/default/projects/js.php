<?php
/**
 * Javascript for Project forms
 *
 * @package Coopfunding
 * @subpackage Projects
 */
?>

// this adds a class to support IE8 and older
elgg.register_hook_handler('init', 'system', function() {
	// jQuery uses 0-based indexing
	$('#projects-tools').children('li:even').addClass('odd');
});
