<?php

    /**
     * TinyMCE wysiwyg editor
     * @package ElggTinyMCE
     **/
  
    function tinymce_init() {
			
	    // Load system configuration
		    global $CONFIG;
		    
         // Add our CSS
				elgg_extend_view('css','tinymce/css');
				set_view_location('embed/addcontentjs',$CONFIG->pluginspath . 'tinymce/views/');
     }
     
     // Make sure the status initialisation function is called on initialisation
		register_elgg_event_handler('init','system','tinymce_init',9999);
       
?>