<?php

     /**
     * ZAudio - a simple mp3 player
     * A simple plugin to play mp3 files on the page
     * http://wpaudioplayer.com/license
     * http://wpaudioplayer.com/standalone
     * @package ElggZAudio
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
     **/
  
    function zaudio_init() {
		    
     }
     
     // Make sure the status initialisation function is called on initialisation
		register_elgg_event_handler('init','system','zaudio_init',999);
		
?>