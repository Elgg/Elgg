<?php
 /**
 * ZAudio - a simple mp3 player
 * A simple plugin to play mp3 files on the page
 * http://wpaudioplayer.com/license
 * http://wpaudioplayer.com/standalone
 *
 * @package ElggZAudio
 */

elgg_register_event_handler('init', 'system', 'zaudio_init');

function zaudio_init() {
	elgg_extend_view('css/screen', 'zaudio/css');
}
