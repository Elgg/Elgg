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
     
?>
<!-- include the audioplayer js code -->
<script type="text/javascript" src="<?php echo $vars['url']; ?>mod/zaudio/audioplayer/audio-player.js"></script>  
<!-- set up the player -->
<script type="text/javascript">  
    AudioPlayer.setup("<?php echo $vars['url']; ?>mod/zaudio/audioplayer/player.swf", {  
         width: 290  
    });  
</script>  

<div style="margin:10px 0 10px 10px;">
<p id="audioplayer_1">Alternative content</p>  
         <script type="text/javascript">  
         AudioPlayer.embed("audioplayer_1", {soundFile: "<?php echo $vars['url']; ?>mod/file/download.php?file_guid=<?php echo $vars['entity']->getGUID(); ?>"});  
         </script> 
</div> 
 