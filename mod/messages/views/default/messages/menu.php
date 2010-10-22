<?php
/**
 * Elgg hoverover extender for messages
 * 
 * @package ElggMessages
 */

// login check already performed in profile/icon
?>
<li class="user_menu_profile">
	<a class="send_message" href="<?php echo $vars['url']; ?>mod/messages/send.php?send_to=<?php echo $vars['entity']->guid; ?>"><?php echo elgg_echo("messages:sendmessage"); ?></a>	
</li>