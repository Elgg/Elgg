<?php
/**
 * Initialize the TinyMCE script
 */

elgg_register_js('tinymce', 'mod/tinymce/vendor/tinymce/jscripts/tiny_mce/tiny_mce.js');
elgg_register_js('elgg.tinymce', elgg_get_simplecache_url('js', 'tinymce'));