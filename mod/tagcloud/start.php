<?php
	function tagcloud_init() {
    add_widget_type('tagcloud', elgg_echo('tagcloud:widget:title'), elgg_echo('tagcloud:widget:description'));
    
	// Extend CSS
	extend_view('css','tagcloud/css');
	
	if(is_plugin_enabled('blog')) {
		// extend blog sidebar with a tag-cloud
	}
	if(is_plugin_enabled('bookmarks')) {
		// extend bkmrks sidebar with a tag-cloud
	}
				     
  }
			
  register_elgg_event_handler('init', 'system', 'tagcloud_init');	
?>
