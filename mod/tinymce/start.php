<?php

    /**
     * TinyMCE wysiwyg editor
     * @package ElggTinyMCE
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
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