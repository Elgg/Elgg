<?php

     /**
     * ZAudio - a simple mp3 player
     * A simple plugin to play mp3 files on the page
     * http://wpaudioplayer.com/license
     * http://wpaudioplayer.com/standalone
     * @package ElggZAudio
     **/
  
    function zaudio_init() {
		    
     }
     
     // Make sure the status initialisation function is called on initialisation
		register_elgg_event_handler('init','system','zaudio_init',999);
		
?>