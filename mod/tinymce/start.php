<?php
/**
 * TinyMCE wysiwyg editor
 *
 * @package TinyMCE
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 **/

function tinymce_init() {
	global $CONFIG;

	elgg_extend_view('css', 'tinymce/css');
	elgg_extend_view('embed/custom_insert_js', 'tinymce/embed_custom_insert_js');
}

register_elgg_event_handler('init', 'system', 'tinymce_init', 9999);
