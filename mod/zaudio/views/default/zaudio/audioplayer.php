<?php
/**
 * ZAudio audio player
 * @package ElggZAudio
 */

elgg_require_js('elgg/zaudio');

$player_options = [
	'soundFile' => elgg_get_site_url() . "file/download/{$vars['file_guid']}",
	// more options: http://wpaudioplayer.com/standalone/
];

?>
<div class="zaudio">
	<?php echo elgg_format_element('div', [
		'data-zaudio-player' => json_encode($player_options),
	]); ?>
</div>
