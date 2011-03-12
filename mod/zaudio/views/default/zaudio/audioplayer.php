<?php
/**
 * ZAudio audio player
 * @package ElggZAudio
 */

$js_url = elgg_get_site_url() . 'mod/zaudio/audioplayer/audio-player.js';
elgg_register_js('elgg.zaudio', $js_url);

$swf_url = elgg_get_site_url() . 'mod/zaudio/audioplayer/player.swf';
$mp3_url = elgg_get_site_url() . "mod/file/download.php?file_guid={$vars['file_guid']}";

?>
<?php //@todo JS 1.8: no ?>
<script type="text/javascript">
	AudioPlayer.setup("<?php echo $swf_url; ?>", {width: 290});
</script>

<div class="zaudio">
	<p id="zaudioplayer"></p>
	<script type="text/javascript">
		AudioPlayer.embed("zaudioplayer", {soundFile: "<?php echo $mp3_url; ?>"});
	</script>
</div>